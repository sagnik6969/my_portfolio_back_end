<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Project::with('skills')->latest()->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'github_link' => 'required|url',
                'live_link' => 'required|url',
                'skills' => 'nullable|array'
            ]
        );

        $skills = $data['skills'] ?? [];

        unset($data['skills']);

        $project = null;

        DB::transaction(function () use (&$project, $data, $skills) {

            $project = Project::create($data);

            foreach ($skills as $skill) {
                $skill = Skill::where('title', $skill)->first();

                if ($skill) {
                    $project->projectSkills()->create([
                        'skill_id' => $skill->id
                    ]);
                }

            }

        });


        $project->load('skills');

        return response()->json($project);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
