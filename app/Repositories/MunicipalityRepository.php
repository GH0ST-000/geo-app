<?php

namespace App\Repositories;

use App\Models\Municipality;
use Illuminate\Support\Collection;

class MunicipalityRepository
{
    protected $model;

    public function __construct(Municipality $model)
    {
        $this->model = $model;
    }

    /**
     * Get all municipalities
     * 
     * @param string $locale
     * @return Collection
     */
    public function getAll(string $locale = 'en'): Collection
    {
        $municipalities = $this->model->all();
        
        if ($locale === 'ka') {
            return $this->transformForGeorgian($municipalities);
        }
        
        return $this->transformForEnglish($municipalities);
    }

    /**
     * Get municipalities by region
     * 
     * @param string $region
     * @param string $locale
     * @return Collection
     */
    public function getByRegion(string $region, string $locale = 'en'): Collection
    {
        $regionField = $locale === 'ka' ? 'region_ka' : 'region_en';
        $municipalities = $this->model->where($regionField, $region)->get();
        
        if ($locale === 'ka') {
            return $this->transformForGeorgian($municipalities);
        }
        
        return $this->transformForEnglish($municipalities);
    }

    /**
     * Find municipality by ID
     * 
     * @param int $id
     * @param string $locale
     * @return array|null
     */
    public function findById(int $id, string $locale = 'en'): ?array
    {
        $municipality = $this->model->find($id);
        
        if (!$municipality) {
            return null;
        }
        
        if ($locale === 'ka') {
            return $this->transformSingleForGeorgian($municipality);
        }
        
        return $this->transformSingleForEnglish($municipality);
    }

    /**
     * Search municipalities
     * 
     * @param string $query
     * @param string $locale
     * @return Collection
     */
    public function search(string $query, string $locale = 'en'): Collection
    {
        if ($locale === 'ka') {
            $municipalities = $this->model->where('name_ka', 'like', "%{$query}%")
                ->orWhere('region_ka', 'like', "%{$query}%")
                ->get();
            
            return $this->transformForGeorgian($municipalities);
        }
        
        $municipalities = $this->model->where('name_en', 'like', "%{$query}%")
            ->orWhere('region_en', 'like', "%{$query}%")
            ->get();
        
        return $this->transformForEnglish($municipalities);
    }
    
    /**
     * Transform the collection for English locale
     * 
     * @param Collection $municipalities
     * @return Collection
     */
    protected function transformForEnglish(Collection $municipalities): Collection
    {
        return $municipalities->map(function ($municipality) {
            return $this->transformSingleForEnglish($municipality);
        });
    }
    
    /**
     * Transform a single municipality for English locale
     * 
     * @param Municipality $municipality
     * @return array
     */
    protected function transformSingleForEnglish(Municipality $municipality): array
    {
        return [
            'id' => $municipality->id,
            'name' => $municipality->name_en,
            'region' => $municipality->region_en
        ];
    }
    
    /**
     * Transform the collection for Georgian locale
     * 
     * @param Collection $municipalities
     * @return Collection
     */
    protected function transformForGeorgian(Collection $municipalities): Collection
    {
        return $municipalities->map(function ($municipality) {
            return $this->transformSingleForGeorgian($municipality);
        });
    }
    
    /**
     * Transform a single municipality for Georgian locale
     * 
     * @param Municipality $municipality
     * @return array
     */
    protected function transformSingleForGeorgian(Municipality $municipality): array
    {
        // Log municipality data for debugging
        \Illuminate\Support\Facades\Log::info('Municipality Georgian data:', [
            'id' => $municipality->id,
            'name_ka' => $municipality->name_ka,
            'region_ka' => $municipality->region_ka
        ]);
        
        return [
            'id' => $municipality->id,
            'name' => $municipality->name_ka,
            'region' => $municipality->region_ka
        ];
    }
} 