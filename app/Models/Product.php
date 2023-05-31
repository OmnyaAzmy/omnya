<?php

namespace App\Models;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title','newPrice','oldPrice','offer','category','color','size'  , 'abstract','featuer','pin_code','description','videos','is_complete','is_special','user_id'
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRating($query)
    {
        $query->addSelect(\DB::raw('(
            SELECT sum(reviews.star) / count(*) FROM reviews where reviews.product_id = products.id LIMIT 1
        ) AS rating'));
    }

    /**
     * Returns a list of the allowed files to be uploaded.
     *
     * @return array
     */
    public static function formRequestFileKeys(): array
    {
        return [
            'image' => [
                'image_1',
            ],
            'images' => [
                'image_2',
                'image_3',
                'image_4',
                'image_5',
                'image_6',
            ],
        ];
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('images')
            ->acceptsFile(function (File $file) {
                return in_array($file->mimeType, config('media-library.allowed_image_types'));
            })
            ->useFallbackUrl(asset('assets/img/no-image.jpg'));

        $this
            ->addMediaCollection('image')
            ->singleFile()
            ->acceptsFile(function (File $file) {
                return in_array($file->mimeType, config('media-library.allowed_image_types'));
            })
            ->useFallbackUrl(asset('assets/img/no-image.jpg'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        foreach (['50', '200'] as $size) {
            $this->addMediaConversion($size)
                ->performOnCollections('images', 'image')
                ->format(Manipulations::FORMAT_JPG)
                ->quality(90)
                ->fit(Manipulations::FIT_CROP, $size, $size)
                ->optimize()
                ->{$size == 200 ? 'nonQueued' : 'queued'}();
        }

        $this->addMediaConversion('large')
            ->performOnCollections('images', 'image')
            ->format(Manipulations::FORMAT_JPG)
            ->quality(90)
            // ->width(1200)
            ->fit(Manipulations::FIT_CROP, 1200, ceil(1200 / 16 * 9))
            ->optimize();

        $this->addMediaConversion('medium')
            ->performOnCollections('images', 'image')
            ->format(Manipulations::FORMAT_JPG)
            ->quality(90)
            ->width(640)
            // ->fit(Manipulations::FIT_CROP, 640, ceil(640/16*9))
            ->optimize();

        $this->addMediaConversion('small')
            ->performOnCollections('images', 'image')
            ->format(Manipulations::FORMAT_JPG)
            ->quality(90)
            ->fit(Manipulations::FIT_CROP, 360, ceil(360 / 4 * 3))
            // ->width(360)
            ->optimize();
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function wishlist()
    {
        return $this->hasOne(WishList::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function orderItem()
    {
        return $this->hasOne(OrderItem::class);
    }
}
