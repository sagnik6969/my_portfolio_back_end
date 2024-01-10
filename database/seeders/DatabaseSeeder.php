<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $skills = [
            "HTML" => 'language-html5',
            "CSS" => 'language-css3',
            "JAVASCRIPT" => 'language-javascript',
            "VUE.JS" => 'vuejs',
            "PHP" => 'language-php',
            "LARAVEL" => 'laravel',
            "ALGORITHMS" => 'graph'
        ];


        foreach ($skills as $skill => $icon) {
            Skill::create([
                'title' => $skill,
                'icon_name' => $icon
            ]);
        }

        // Project::factory(15)->create();

        // $storedSkills = Skill::all();
        // $storedProjects = Project::all();

        // foreach ($storedProjects as $project) {
        //     $randomSkills = $storedSkills->random(rand(1, 6));

        //     foreach ($randomSkills as $skill) {
        //         $project->projectSkills()->create([
        //             'skill_id' => $skill->id
        //         ]);
        //     }
        // }

        User::create([
            'name' => 'Sagnik Jana',
            'email' => 'sagnikjana668@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
