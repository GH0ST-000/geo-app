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
        'other_standard',
        'images',
        'documents',
        'videos',
        'audio',
        'spreadsheets'
    ];

    /**
     * File type mappings for better categorization
     */
    protected $fileTypeMap = [
        // Documents
        'txt' => 'document',
        'doc' => 'document',
        'docx' => 'document',
        'pdf' => 'document',
        'odt' => 'document',
        'rtf' => 'document',
        'tex' => 'document',
        'wps' => 'document',
        
        // Spreadsheets
        'xls' => 'spreadsheet',
        'xlsx' => 'spreadsheet',
        'ods' => 'spreadsheet',
        'csv' => 'spreadsheet',
        'tsv' => 'spreadsheet',
        
        // Videos
        'mp4' => 'video',
        'avi' => 'video',
        'mkv' => 'video',
        'mov' => 'video',
        'wmv' => 'video',
        'flv' => 'video',
        'webm' => 'video',
        
        // Audio
        'mp3' => 'audio',
        'wav' => 'audio',
        'aac' => 'audio',
        'flac' => 'audio',
        'ogg' => 'audio',
        'm4a' => 'audio',
        
        // Images
        'jpg' => 'image',
        'jpeg' => 'image',
        'png' => 'image',
        'gif' => 'image',
        'bmp' => 'image',
        'svg' => 'image',
        'webp' => 'image',
        'tiff' => 'image',
        'tif' => 'image',
        'heic' => 'image',
        'heif' => 'image',
        
        // Other
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
            'file' => 'required|file|max:50000', // 50MB max size to allow for larger files
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

        // Store the file
        $path = $file->store("users/{$user->id}/standards/{$slug}");

        // Create the standard record
        $standard = new UserStandard([
            'user_id' => $user->id,
            'slug' => $slug,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'file_path' => $path,
            'file_extension' => $extension,
            'file_category' => $fileCategory,
        ]);

        $standard->save();

        // Add file URL to response
        $standard->file_url = $standard->file_url;

        return response()->json([
            'message' => 'File uploaded successfully',
            'standard' => $standard,
            'file_category' => $fileCategory,
            'is_media' => in_array($fileCategory, ['image', 'video', 'audio'])
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

        // Optional filtering by file category
        if ($request->has('category')) {
            $category = $request->input('category');
            $extensions = array_keys(array_filter($this->fileTypeMap, function($value) use ($category) {
                return $value === $category;
            }));
            
            if (!empty($extensions)) {
                $query->where(function($q) use ($extensions) {
                    foreach ($extensions as $ext) {
                        $q->orWhere('file_name', 'like', '%.' . $ext);
                    }
                });
            }
        }

        $standards = $query->latest()->get();

        // Add file information to each standard
        $standards->each(function($standard) {
            $standard->file_url = $standard->file_url;
            
            // Add file type categorization if not already set
            if (!isset($standard->file_category)) {
                $extension = strtolower(pathinfo($standard->file_name, PATHINFO_EXTENSION));
                $standard->file_category = $this->fileTypeMap[$extension] ?? 'other';
            }
            
            $standard->is_image = ($standard->file_category === 'image');
            $standard->is_video = ($standard->file_category === 'video');
            $standard->is_audio = ($standard->file_category === 'audio');
            $standard->is_document = ($standard->file_category === 'document');
            $standard->is_spreadsheet = ($standard->file_category === 'spreadsheet');
            $standard->is_media = in_array($standard->file_category, ['image', 'video', 'audio']);
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
            'message' => 'File deleted successfully'
        ]);
    }
}
