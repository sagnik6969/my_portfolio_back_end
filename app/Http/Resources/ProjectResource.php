<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'github_link' => $this->github_link,
            'live_link' => $this->live_link,
            'image_link' => env('APP_URL', '') . '/storage/' . $this->image_link,
            'skills' => SkillResource::collection($this->whenLoaded('skills')),

        ];
    }

}
