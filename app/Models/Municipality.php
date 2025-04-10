<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_en',
        'name_ka',
        'region_en',
        'region_ka'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get the name based on the language.
     *
     * @param string $locale
     * @return string
     */
    public function getName(string $locale = 'en'): string
    {
        return $locale === 'ka' ? $this->name_ka : $this->name_en;
    }

    /**
     * Get the region based on the language.
     *
     * @param string $locale
     * @return string
     */
    public function getRegion(string $locale = 'en'): string
    {
        return $locale === 'ka' ? $this->region_ka : $this->region_en;
    }
} 