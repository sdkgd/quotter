<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id'=>$this->id,
            'user_name'=>$this->user_name,
            'display_name'=>$this->display_name,
            'profile'=>$this->profile,
            'profile_image_id'=>$this->profile_image_id,
            'image'=>new ImageResource($this->whenLoaded('image')),
        ];
    }
}
