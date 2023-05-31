<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' =>  $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'phone' => $this->phone,
        ];
    }
}
