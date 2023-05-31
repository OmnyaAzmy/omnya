<?php

namespace App\Http\Resources;

use App\Http\Resources\ReviewResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'title' => $this->title,
            'newPrice' => $this->newPrice,
            'oldPrice' => $this->oldPrice,
            'offer' => $this->offer,
            'reviews_count' => $this->reviews_count,
            'rating' => $this->rating,
            'is_complete' => $this->is_complete,
            'is_special' => $this->is_special,


            'details' => [
                'abstract' => $this->abstract,
                'pin_code' => $this->pin_code,
                'description' => $this->description,
                'videos' => [$this->videos],
                'video_html' => $this->when($this->videos, function () {
                    $query_str = parse_url($this->videos, PHP_URL_QUERY);

                    if (! $query_str) {
                        return null;
                    }

                    parse_str($query_str, $params);

                    if (! isset($params['v'])) {
                        return null;
                    }

                    return '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $params['v'] . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
                }, null),

                'colors' => $this->when($this->color, function () {
                    return collect(explode(',', $this->color))
                        ->filter(fn ($item) => trim($item))
                        ->map(fn ($item) => ucfirst(trim($item)))
                        ->values();
                }, null),

                'featuers' => $this->when($this->featuer, function () {
                    return collect(explode(',', $this->featuer))
                        ->filter(fn ($item) => trim($item))
                        ->map(fn ($item) => ucfirst(trim($item)))
                        ->values();
                }, null),
                'category' => $this->when($this->category, function () {
                    return collect(explode(',', $this->category))
                        ->filter(fn ($item) => trim($item))
                        ->map(fn ($item) => ucfirst(trim($item)))
                        ->values();
                }, null),
                'sizes' => $this->when($this->size, function () {
                    return collect(explode(',', $this->size))
                        ->filter(fn ($item) => trim($item))
                        ->map(fn ($item) => strtoupper(trim($item)))
                        ->values();
                }, null),
                'images' => $this->whenLoaded('media', function () {
                    $images = [];

                    if ($this->hasMedia('image')) {
                        $images[] =[
                        
                            $this?->getFirstMediaUrl('image')
                        ];
                        for ($i = 2; $i <= 6; $i++) {
                            $media = $this?->getFirstMedia('images', ['form_key' => 'image_' . $i]);

                            if (! $media) {
                                continue;
                            }
                            $images[] = $media?->getUrl();
                        }
                    }

                    return $images;
                }),
                'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            ],

        ];
    }
}
