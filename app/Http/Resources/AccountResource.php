<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar_url' => gravatar($this->email, 96),
            'locale' => $this->locale,
            'timezone' => $this->timezone,
            'plan' => collect($this->plan)->only(['id', 'name', 'features']),
            'default_domain' => $this->default_domain,
            'default_space' => $this->default_space,
            'default_stats' => $this->default_stats,
            'created_at' => $this->created_at
        ];
    }

    public function with($request)
    {
        return [
            'status' => 200
        ];
    }
}
