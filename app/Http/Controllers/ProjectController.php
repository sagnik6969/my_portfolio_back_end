<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return response()->json(Project::with('skills')->latest()->get());
        return ProjectResource::collection(Project::with('skills')->latest()->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create(Request $request)
    // {


    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {

        $image_file = $request->file('image_file');

        $path = $image_file->store('/project_imgs', 'public');

        $data = [
            'title' => request()->title,
            'description' => request()->description,
            'github_link' => request()->github_link,
            'live_link' => request()->live_link,
            'image_link' => $path,
            'skills' => request()->skills,
        ];

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

        $project = Project::findOrFail($project->id);
        // The above line is required because the default image_link value is not added to $project
        // automatically.

        $project->load('skills');

        return (new ProjectResource($project))->response()->setStatusCode(201);
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
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'success' => 'project deleted successfully'
        ]);

    }
}
