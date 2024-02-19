<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    public function test_project_index_returns_a_successful_response_to_unauthenticated_user()
    {
        Project::factory(3)->create();

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200);
        $response->assertJsonCount(3);

        $projects = Project::all();

        $projectsArray = [];

        foreach ($projects as $project) {
            $projectsArray[] = [
                'id' => $project->id,
                'title' => $project->title,
                'description' => $project->description,
                'github_link' => $project->github_link,
                'live_link' => $project->live_link,
                'image_link' => env('APP_URL', '') . '/storage/' . $project->image_link,
                'skills' => [],
            ];
        }

        $response->assertExactJson($projectsArray);
    }

    public function test_project_index_returns_a_successful_response_to_authenticated_user()
    {
        Project::factory(3)->create();
        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200);
        $response->assertJsonCount(3);

        $projects = Project::all();

        $projectsArray = [];

        foreach ($projects as $project) {
            $projectsArray[] = [
                'id' => $project->id,
                'title' => $project->title,
                'description' => $project->description,
                'github_link' => $project->github_link,
                'live_link' => $project->live_link,
                'image_link' => env('APP_URL', '') . '/storage/' . $project->image_link,
                'skills' => [],
            ];
        }

        $response->assertExactJson($projectsArray);

    }

    public function test_it_does_not_allow_creating_of_new_project_to_unauthenticated_user()
    {
        // Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/projects', [
            'title' => fake()->sentence(),
            'description' => fake()->sentence(),
            'github_link' => fake()->url(),
            'live_link' => fake()->url(),
            'image_file' => UploadedFile::fake()->image('image.jpg'),
            'skills' => [],
        ]);

        $response->assertStatus(401);

    }

    public function test_it_allows_creating_of_new_project_to_authenticated_user()
    {
        Sanctum::actingAs(User::factory()->create());

        Storage::fake('public');

        $uploadableImage = UploadedFile::fake()->image('image.jpg');

        $project = [
            'title' => fake()->sentence(),
            'description' => fake()->sentence(),
            'github_link' => fake()->url(),
            'live_link' => fake()->url(),
            'image_file' => $uploadableImage,
            'skills' => [],
        ];

        $response = $this->postJson('/api/projects', $project);
        $response->assertStatus(201);
        Storage::disk('public')->assertExists('project_imgs/' . $uploadableImage->hashName());

        $this->assertDatabaseHas(Project::class, [
            'title' => $project['title'],
            'description' => $project['description'],
            'github_link' => $project['github_link'],
            'live_link' => $project['live_link'],
            'image_link' => 'project_imgs/' . $uploadableImage->hashName(),
        ]);
    }


    public static function projectProvider()
    {

        $project = [
            'title' => fake()->sentence(),
            'description' => fake()->sentence(),
            'github_link' => fake()->url(),
            'live_link' => fake()->url(),
            'image_file' => UploadedFile::fake()->image('image.jpg'),
            'skills' => [],
        ];

        $tests = [];

        foreach ($project as $key => $value) {
            if ($key == 'skills')
                continue;
            $tempArray = $project;
            $tempArray[$key] = null;
            $tests["$key = null"] = [$tempArray];
        }

        return $tests;
    }

    /**
     * @dataProvider projectProvider
     */
    public function test_it_returns_validation_error($project)
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/projects', $project);
        $response->assertStatus(422);

    }

    public function test_it_does_not_return_validation_error_when_skill_is_set_to_null()
    {
        Sanctum::actingAs(User::factory()->create());

        $uploadableImage = UploadedFile::fake()->image('image.jpg');

        $project = [
            'title' => fake()->sentence(),
            'description' => fake()->sentence(),
            'github_link' => fake()->url(),
            'live_link' => fake()->url(),
            'image_file' => $uploadableImage,
            'skills' => null,
        ];

        $response = $this->postJson('/api/projects', $project);

        $response->assertStatus(201);

    }
}
