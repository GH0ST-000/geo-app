<?php

namespace App\Repositories;

use App\Models\Municipality;

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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get municipalities by region
     * 
     * @param string $region
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByRegion(string $region)
    {
        return $this->model->where('region', $region)->get();
    }

    /**
     * Find municipality by ID
     * 
     * @param int $id
     * @return Municipality|null
     */
    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Search municipalities
     * 
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(string $query)
    {
        return $this->model->where('name', 'like', "%{$query}%")
            ->orWhere('region', 'like', "%{$query}%")
            ->get();
    }
} 