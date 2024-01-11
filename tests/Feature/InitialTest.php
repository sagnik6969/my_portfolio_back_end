<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InitialTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_application_runs_successful_response()
    {
        $response = $this->get('/');
        $response->assertOk();
    }

    public function test_if_non_existent_api_route_returns_notFound_response()
    {
        // Checks if non existent api route falls in the spa web route
        $response = $this->get('/api/project');
        $response->assertNotFound();
    }

    public function test_if_non_existent_web_route_returns_a_successful_response()
    {
        // Checks if routing is handled by spa or not
        $response = $this->get('/abc');
        $response->assertOk();

    }



    public function test_if_project_index_returns_a_correct_response()
    {

        Project::factory(10)->create();
        $response = $this->withHeader('accept', 'application/json')->get('/api/projects');
        $response->assertOk();
        $response->assertJsonCount(10);

        // dump($response->decodeResponseJson());
    }




}
