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
use Symfony\Component\Uid\Ulid;
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
        'name',
        'age',
        'is_admin',
        'personal_number',
        'gender',
        'is_verified',
        'description',
        'ulid',
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
        ];
    }

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->ulid)) {
                $model->ulid = (new Ulid())->toBase32();
            }
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'ulid';
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

                $this->addMediaConversion('large')
                    ->width(1200)
                    ->height(1200)
                    ->queued();
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
        if ($media && $media->hasGeneratedConversion('thumb')) {
            return $media->getUrl('thumb');
        }

        // Fall back to original
        if ($media) {
            return $media->getUrl();
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
        if ($media && $media->hasGeneratedConversion('medium')) {
            return $media->getUrl('medium');
        }

        // Fall back to original
        if ($media) {
            return $media->getUrl();
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
        if ($media && $media->hasGeneratedConversion('large')) {
            return $media->getUrl('large');
        }

        // Fall back to original
        if ($media) {
            return $media->getUrl();
        }

        return null;
    }

    /**
     * Get the products owned by the user.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the standards belonging to the user.
     */
    public function standards()
    {
        return $this->hasMany(UserStandard::class);
    }
}
