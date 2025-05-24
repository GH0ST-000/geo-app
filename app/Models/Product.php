<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_name',
        'product_description',
        'packing_capacity',
        'address',
        'is_active',
        'standard',
        'standard_group_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * The "booted" method of the model.
     * This ensures all associated files are properly deleted
     * when a product is deleted.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        // When a product is about to be deleted
        static::deleting(function ($product) {
            // Clear all media collections
            $product->clearMediaCollection('product_images');
            $product->clearMediaCollection('standard_files');
        });
    }

    /**
     * Get the user that owns the product.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Register media collections for the product
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(150)
                    ->height(150)
                    ->queued();
                
                $this->addMediaConversion('medium')
                    ->width(400)
                    ->height(400)
                    ->queued();
            });

        $this->addMediaCollection('standard_files');
    }

    /**
     * Get product images URLs
     *
     * @return array
     */
    public function getProductImagesAttribute()
    {
        $images = $this->getMedia('product_images');
        $urls = [];
        
        foreach ($images as $image) {
            $urls[] = [
                'id' => $image->id,
                'original' => $image->getUrl(),
                'thumb' => $image->hasGeneratedConversion('thumb') 
                    ? $image->getUrl('thumb') 
                    : $image->getUrl(),
                'medium' => $image->hasGeneratedConversion('medium') 
                    ? $image->getUrl('medium') 
                    : $image->getUrl(),
            ];
        }
        
        return $urls;
    }

    /**
     * Get standard files URLs
     *
     * @return array
     */
    public function getStandardFilesAttribute()
    {
        $files = $this->getMedia('standard_files');
        $urls = [];
        
        foreach ($files as $file) {
            $urls[] = [
                'id' => $file->id,
                'name' => $file->name,
                'file_name' => $file->file_name,
                'mime_type' => $file->mime_type,
                'size' => $file->size,
                'url' => $file->getUrl(),
            ];
        }
        
        return $urls;
    }
}
