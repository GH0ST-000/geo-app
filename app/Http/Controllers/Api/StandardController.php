<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserStandard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StandardController extends Controller
{
    /**
     * Valid standard types
     */
    protected $validStandardTypes = [
        'honey_standard',
        'dairy_standard',
        'crop_standard'
    ];
    
    /**
     * Store a new standard file
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'slug' => 'required|string|in:honey_standard,dairy_standard,crop_standard',
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $file = $request->file('file');
        $slug = $request->slug;
        
        // Store the file
        $path = $file->store("users/{$user->id}/standards/{$slug}");
        
        // Create the standard record
        $standard = new UserStandard([
            'user_id' => $user->id,
            'slug' => $slug,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'file_path' => $path,
        ]);
        
        $standard->save();
        
        // Add file URL to response
        $standard->file_url = $standard->file_url;
        
        return response()->json([
            'message' => 'Standard file uploaded successfully',
            'standard' => $standard
        ], 201);
    }
    
    /**
     * Get user standards by type or all
     *
     * @param Request $request
     * @param string|null $slug Optional slug to filter by
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ?string $slug = null)
    {
        $user = Auth::user();
        $query = $user->standards();
        
        // Filter by slug if provided
        if ($slug) {
            if (!in_array($slug, $this->validStandardTypes)) {
                return response()->json([
                    'message' => 'Invalid standard type'
                ], 400);
            }
            
            $query->where('slug', $slug);
        }
        
        $standards = $query->latest()->get();
        
        // Add file URLs to each standard
        $standards->each(function($standard) {
            $standard->file_url = $standard->file_url;
        });
        
        return response()->json($standards);
    }
    
    /**
     * Delete a standard file
     *
     * @param int $id Standard ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $standard = UserStandard::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
            
        if (!$standard) {
            return response()->json([
                'message' => 'Standard not found or you do not have permission to delete it'
            ], 404);
        }
        
        // Delete the file from storage
        if ($standard->file_path) {
            Storage::delete($standard->file_path);
        }
        
        // Delete the record
        $standard->delete();
        
        return response()->json([
            'message' => 'Standard file deleted successfully'
        ]);
    }
}
