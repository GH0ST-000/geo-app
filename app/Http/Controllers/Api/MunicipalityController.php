<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\MunicipalityRepository;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    protected $municipalityRepository;

    public function __construct(MunicipalityRepository $municipalityRepository)
    {
        $this->municipalityRepository = $municipalityRepository;
    }

    /**
     * Get all municipalities or search by query
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // If search query is provided, search municipalities
        if ($request->has('search')) {
            $municipalities = $this->municipalityRepository->search($request->search);
            return response()->json($municipalities);
        }

        // If region is provided, filter by region
        if ($request->has('region')) {
            $municipalities = $this->municipalityRepository->getByRegion($request->region);
            return response()->json($municipalities);
        }

        // Otherwise, return all municipalities
        $municipalities = $this->municipalityRepository->getAll();
        return response()->json($municipalities);
    }

    /**
     * Get a specific municipality by ID
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $municipality = $this->municipalityRepository->findById($id);
        
        if (!$municipality) {
            return response()->json([
                'message' => 'Municipality not found'
            ], 404);
        }
        
        return response()->json($municipality);
    }
} 