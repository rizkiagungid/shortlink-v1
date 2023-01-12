<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
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
            'alias' => $this->alias,
            'url' => $this->url,
            'short_url' => (($this->domain->url ?? config('app.url')) .'/'.$this->alias),
            'title' => $this->title,
            'target_type' => $this->target_type,
            'country_target' => $this->country_target,
            'platform_target' => $this->platform_target,
            'language_target' => $this->language_target,
            'rotation_target' => $this->rotation_target,
            'last_rotation' => $this->last_rotation,
            'disabled' => $this->disabled,
            'privacy' => $this->privacy,
            'privacy_password' => ($this->privacy_password ? true : false),
            'password' => ($this->password ? true : false),
            'expiration_url' => $this->expiration_url,
            'expiration_clicks' => $this->expiration_clicks,
            'clicks' => $this->clicks,
            'space' => $this->space,
            'domain' => $this->domain,
            'pixels' => $this->pixels,
            'ends_at' => $this->ends_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'status' => 200
        ];
    }
}
