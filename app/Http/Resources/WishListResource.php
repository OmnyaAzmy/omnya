<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WishListResource extends JsonResource
{
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
            //'quantity' => $this->quantity,
            'product' => new ProductResource($this->whenLoaded('product')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
