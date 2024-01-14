<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'github_link',
        'live_link',
    ];
    public function projectSkills(): HasMany
    {
        return $this->hasMany(ProjectSkill::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'project_skills');
    }
}
