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
        // Get language preference from Accept-Language header
        $locale = $this->getLocaleFromRequest($request);

        // If search query is provided, search municipalities
        if ($request->has('search')) {
            $municipalities = $this->municipalityRepository->search($request->search, $locale);
            return response()->json($municipalities);
        }

        // If region is provided, filter by region
        if ($request->has('region')) {
            $municipalities = $this->municipalityRepository->getByRegion($request->region, $locale);
            return response()->json($municipalities);
        }

        // Otherwise, return all municipalities
        $municipalities = $this->municipalityRepository->getAll($locale);
        return response()->json($municipalities);
    }

    /**
     * Get a specific municipality by ID
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        // Get language preference from Accept-Language header
        $locale = $this->getLocaleFromRequest($request);
        
        $municipality = $this->municipalityRepository->findById($id, $locale);
        
        if (!$municipality) {
            return response()->json([
                'message' => 'Municipality not found'
            ], 404);
        }
        
        return response()->json($municipality);
    }
    
    /**
     * Get locale from request
     * 
     * @param Request $request
     * @return string
     */
    protected function getLocaleFromRequest(Request $request): string
    {
        $acceptLanguage = $request->header('Accept-Language');
        
        // Log the received Accept-Language header for debugging
        \Illuminate\Support\Facades\Log::info('Accept-Language header: ' . ($acceptLanguage ?: 'null'));
        
        if ($acceptLanguage && (strtolower($acceptLanguage) === 'ka' || str_starts_with(strtolower($acceptLanguage), 'ka-'))) {
            return 'ka';
        }
        
        return 'en';
    }
}
