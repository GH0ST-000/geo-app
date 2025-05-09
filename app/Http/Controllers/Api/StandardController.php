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
        'crop_standard',
        // Add any additional categories as needed
        'other_standard',
        'images',
        'documents'
    ];

    /**
     * File type mappings for better categorization
     */
    protected $fileTypeMap = [
        // Images
        'jpg' => 'image',
        'jpeg' => 'image',
        'png' => 'image',
        'gif' => 'image',
        'svg' => 'image',
        'webp' => 'image',
        'bmp' => 'image',
        'heic' => 'image',
        'heif' => 'image',
        
        // Documents
        'pdf' => 'document',
        'doc' => 'document',
        'docx' => 'document',
        'xls' => 'spreadsheet',
        'xlsx' => 'spreadsheet',
        'ppt' => 'presentation',
        'pptx' => 'presentation',
        'txt' => 'document',
        'rtf' => 'document',
        'odt' => 'document',
        'ods' => 'spreadsheet',
        'odp' => 'presentation',
        
        // Other
        'csv' => 'data',
        'json' => 'data',
        'xml' => 'data',
        'zip' => 'archive',
        'rar' => 'archive'
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
            'slug' => 'required|string|in:' . implode(',', $this->validStandardTypes),
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp,bmp,heic,heif,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,rtf,odt,ods,odp,csv,json,xml,zip,rar|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $file = $request->file('file');
        $slug = $request->slug;

        // Get file extension and determine file type category
        $extension = strtolower($file->getClientOriginalExtension());
        $fileCategory = $this->fileTypeMap[$extension] ?? 'other';
        $isImage = ($fileCategory === 'image');

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
            'message' => 'File uploaded successfully',
            'standard' => $standard,
            'file_category' => $fileCategory,
            'is_image' => $isImage
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
                    'message' => 'Invalid standard type. Valid types are: ' . implode(', ', $this->validStandardTypes)
                ], 400);
            }

            $query->where('slug', $slug);
        }

        $standards = $query->latest()->get();

        // Add file URLs to each standard
        $standards->each(function($standard) {
            $standard->file_url = $standard->file_url;
            
            // Add file type categorization
            $extension = strtolower(pathinfo($standard->file_name, PATHINFO_EXTENSION));
            $standard->file_category = $this->fileTypeMap[$extension] ?? 'other';
            $standard->is_image = ($standard->file_category === 'image');
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
