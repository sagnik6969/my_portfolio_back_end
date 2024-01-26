<?php

namespace Tests\Feature;

use App\Models\Project;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProjectHttpTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_if_project_index_api_returns_a_correct_response()
    {

        $this->seed(TestSeeder::class);

        // $response = $this->withHeader('accept', 'application/json')->get('/api/projects');
        // or
        $response = $this->getJson('/api/projects');
        // automatically sets accept to application/json

        $response->assertOk();
        $response->assertJsonCount(15);


        // we can also pass an array to assertJson mentioning expected key and values
        // if we pass an array we don't need to pass all the keys to return true, for exact matching
        // use assertExactJson 
        $response->assertJson(function (AssertableJson $json) {
            $json->count(15)
                // ->each(function (AssertableJson $json) // test all the elements
                ->first(function (AssertableJson $json) { // test only the first element of the array
                    $json->whereType('id', 'integer')
                        ->whereType('title', 'string')
                        ->whereType('description', 'string')
                        ->whereType('github_link', 'string')
                        ->whereType('live_link', 'string')
                        ->whereType('image_link', 'string')
                        // ->whereType('created_at', 'string')
                        // ->whereType('updated_at', 'string')
                        ->whereType('skills', 'array')
                        ->where('skills', function ($skills) {
                        // dump($skills);
                        foreach ($skills as $skill) {
                            if (!is_int($skill['id']))
                                return false;
                            if (!is_string($skill['title']))
                                return false;
                            if (!is_string($skill['icon_name']))
                                return false;
                        }
                        return true;
                    });
                    // Etc tells laravel there may exist other properties except
                    // the ones specified in where. 
    
                });
        });

        $response->assertJsonPath('0.title', fn($title) => is_string($title));
        // to test value at specific path.



        // $response->assertJsonPath('user.name',sagnik)
        // $jsonResponse = $response->json();
        // validates the json and converts it into an php array

    }


    // public function test_project_index_web_returns_a_correct_response()
    // {
    //     $this->seed(TestSeeder::class);

    //     $response = $this->get('/projects');

    //     $response->assertSeeText('Projects');
    // }
}
