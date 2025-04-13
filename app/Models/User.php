<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'city',
        'phone',
        'user_type',
        'profile_picture',
        'is_verified',
        'description',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'profile_picture',
        'profile_picture_url'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Register media collections for the user
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_picture')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(150)
                    ->height(150)
                    ->keepOriginalImageFormat()
                    ->nonQueued();

                $this->addMediaConversion('medium')
                    ->width(400)
                    ->height(400)
                    ->keepOriginalImageFormat()
                    ->nonQueued();

                $this->addMediaConversion('large')
                    ->width(1200)
                    ->height(1200)
                    ->keepOriginalImageFormat()
                    ->nonQueued();
            });
    }

    /**
     * Get profile picture URL
     *
     * @return string|null
     */
    public function getProfilePictureUrlAttribute()
    {
        // First try to get from Media Library
        $media = $this->getFirstMedia('profile_picture');
        if ($media) {
            return $media->getUrl();
        }

        // Fall back to old profile_picture field if it exists
        if ($this->profile_picture) {
            return $this->profile_picture;
        }

        return null;
    }

    /**
     * Get thumbnail URL
     *
     * @return string|null
     */
    public function getProfileThumbnailUrlAttribute()
    {
        $media = $this->getFirstMedia('profile_picture');
        if ($media) {
            return $media->getUrl('thumb');
        }

        return null;
    }

    /**
     * Get medium-sized profile picture URL
     *
     * @return string|null
     */
    public function getProfileMediumUrlAttribute()
    {
        $media = $this->getFirstMedia('profile_picture');
        if ($media) {
            return $media->getUrl('medium');
        }

        return null;
    }

    /**
     * Get large-sized profile picture URL
     *
     * @return string|null
     */
    public function getProfileLargeUrlAttribute()
    {
        $media = $this->getFirstMedia('profile_picture');
        if ($media) {
            return $media->getUrl('large');
        }

        return null;
    }
}
