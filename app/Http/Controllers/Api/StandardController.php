<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserStandard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        'spreadsheets',
        'binary'
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
        'rar' => 'archive',
        
        // Binary and executable formats
        'bin' => 'binary',
        'exe' => 'binary',
        'dll' => 'binary',
        'so' => 'binary',
        'dylib' => 'binary',
        'class' => 'binary',
        'jar' => 'binary',
        'dat' => 'binary',
        'iso' => 'binary',
        'img' => 'binary',
        'apk' => 'binary',
        'dmg' => 'binary'
    ];

    /**
     * Store a new standard file
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // First, just validate the slug without file validation
        $validator = Validator::make($request->all(), [
            'slug' => 'required|string|in:' . implode(',', $this->validStandardTypes),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $slug = $request->slug;

        // Debug information
        $requestInfo = [
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'has_file' => $request->hasFile('file'),
            'all_fields' => array_keys($request->all()),
            'all_files' => $request->allFiles(),
        ];

        // Check if files are present in the request
        $uploadedFiles = [];
        
        // Handle array-style uploads (file[])
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            
            // If it's a single file, convert to array for consistent handling
            if (!is_array($files)) {
                $files = [$files];
            }
            
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $uploadedFiles[] = $file;
                }
            }
        }
        
        if (empty($uploadedFiles)) {
            return response()->json([
                'message' => 'No valid files were uploaded',
                'request_info' => $requestInfo
            ], 422);
        }
        
        // Generate a group ID for this upload batch
        $groupId = (string) Str::uuid();
        $results = [];
        
        // Process each uploaded file
        foreach ($uploadedFiles as $file) {
            // Get file details
            $extension = strtolower($file->getClientOriginalExtension());
            $fileType = $file->getClientMimeType() ?: 'application/octet-stream';
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            
            // Check file size manually (50MB max)
            if ($fileSize > 50000000) {
                $results[] = [
                    'original_name' => $originalName,
                    'success' => false,
                    'message' => 'The file size exceeds the maximum allowed (50MB)'
                ];
                continue;
            }
            
            // Determine file category
            $fileCategory = $this->fileTypeMap[$extension] ?? 'binary';
            
            try {
                // Store the file
                $path = $file->store("users/{$user->id}/standards/{$slug}");
                
                // Create the standard record
                $standard = new UserStandard([
                    'user_id' => $user->id,
                    'group_id' => $groupId,
                    'slug' => $slug,
                    'file_name' => $originalName,
                    'file_type' => $fileType,
                    'file_path' => $path,
                    'file_extension' => $extension,
                    'file_category' => $fileCategory,
                ]);
                
                $standard->save();
                
                // Add file URL to response
                $standard->file_url = $standard->file_url;
                
                $results[] = [
                    'success' => true,
                    'original_name' => $originalName,
                    'standard' => $standard,
                    'file_category' => $fileCategory,
                    'is_media' => in_array($fileCategory, ['image', 'video', 'audio'])
                ];
                
            } catch (\Exception $e) {
                $results[] = [
                    'original_name' => $originalName,
                    'success' => false,
                    'message' => 'Error uploading file: ' . $e->getMessage()
                ];
            }
        }
        
        // Return a single success response if only one file was uploaded
        if (count($results) === 1) {
            if ($results[0]['success']) {
                return response()->json([
                    'message' => 'File uploaded successfully',
                    'standard' => $results[0]['standard'],
                    'file_category' => $results[0]['file_category'],
                    'is_media' => $results[0]['is_media'],
                    'group_id' => $groupId
                ], 201);
            } else {
                return response()->json([
                    'message' => $results[0]['message'],
                    'request_info' => $requestInfo
                ], 422);
            }
        }
        
        // Return a summary response for multiple files
        $successCount = count(array_filter($results, function($item) {
            return $item['success'];
        }));
        
        // Get all successful standards
        $standards = array_map(function($item) {
            return $item['success'] ? $item['standard'] : null;
        }, $results);
        $standards = array_filter($standards);
        
        return response()->json([
            'message' => "{$successCount} of " . count($results) . " files uploaded successfully",
            'results' => $results,
            'standards' => $standards,
            'group_id' => $groupId,
            'debug_info' => $requestInfo
        ], $successCount > 0 ? 201 : 422);
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
        
        // Group files by group_id when requested
        $grouped = $request->has('grouped') && $request->input('grouped') == 'true';
        
        if ($grouped) {
            // First get all distinct group_ids
            $groupIds = $query->whereNotNull('group_id')
                             ->select('group_id')
                             ->distinct()
                             ->pluck('group_id')
                             ->toArray();
            
            // For each group, get the first file to represent the group
            $standards = collect();
            foreach ($groupIds as $groupId) {
                $firstFile = UserStandard::where('group_id', $groupId)
                                        ->where('user_id', $user->id)
                                        ->first();
                
                if ($firstFile) {
                    // Add a count of related files
                    $fileCount = UserStandard::where('group_id', $groupId)->count();
                    $firstFile->file_count = $fileCount;
                    $firstFile->is_group = true;
                    
                    $standards->push($firstFile);
                }
            }
            
            // Add single files (without group_id)
            $singleFiles = $query->whereNull('group_id')->get();
            foreach ($singleFiles as $file) {
                $file->file_count = 1;
                $file->is_group = false;
                $standards->push($file);
            }
            
            // Sort by created_at
            $standards = $standards->sortByDesc('created_at')->values();
        } else {
            // Return all files individually (original behavior)
            $standards = $query->latest()->get();
        }

        // Add file information to each standard
        $standards->each(function($standard) {
            $standard->file_url = $standard->file_url;
            
            // Add file type categorization if not already set
            if (!isset($standard->file_category)) {
                $extension = strtolower(pathinfo($standard->file_name, PATHINFO_EXTENSION));
                $standard->file_category = $this->fileTypeMap[$extension] ?? 'binary';
            }
            
            $standard->is_image = ($standard->file_category === 'image');
            $standard->is_video = ($standard->file_category === 'video');
            $standard->is_audio = ($standard->file_category === 'audio');
            $standard->is_document = ($standard->file_category === 'document');
            $standard->is_spreadsheet = ($standard->file_category === 'spreadsheet');
            $standard->is_binary = ($standard->file_category === 'binary');
            $standard->is_media = in_array($standard->file_category, ['image', 'video', 'audio']);
        });

        return response()->json($standards);
    }
    
    /**
     * Get all files in a group
     *
     * @param string $groupId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroup($groupId)
    {
        $user = Auth::user();
        $standards = UserStandard::where('group_id', $groupId)
                                ->where('user_id', $user->id)
                                ->latest()
                                ->get();
        
        if ($standards->isEmpty()) {
            return response()->json([
                'message' => 'Group not found or you do not have permission to view it'
            ], 404);
        }
        
        // Add file information to each standard
        $standards->each(function($standard) {
            $standard->file_url = $standard->file_url;
            
            if (!isset($standard->file_category)) {
                $extension = strtolower(pathinfo($standard->file_name, PATHINFO_EXTENSION));
                $standard->file_category = $this->fileTypeMap[$extension] ?? 'binary';
            }
            
            $standard->is_image = ($standard->file_category === 'image');
            $standard->is_video = ($standard->file_category === 'video');
            $standard->is_audio = ($standard->file_category === 'audio');
            $standard->is_document = ($standard->file_category === 'document');
            $standard->is_spreadsheet = ($standard->file_category === 'spreadsheet');
            $standard->is_binary = ($standard->file_category === 'binary');
            $standard->is_media = in_array($standard->file_category, ['image', 'video', 'audio']);
        });
        
        return response()->json([
            'group_id' => $groupId,
            'files' => $standards,
            'count' => $standards->count()
        ]);
    }

    /**
     * Delete a standard file
     *
     * @param Request $request
     * @param int $id Standard ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
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

        // Check if this is part of a group
        $groupId = $standard->group_id;
        $deleteGroup = $request->input('delete_group', false) && $groupId;
        
        if ($deleteGroup) {
            // Delete all files in the group
            $groupFiles = UserStandard::where('group_id', $groupId)
                                   ->where('user_id', $user->id)
                                   ->get();
            
            foreach ($groupFiles as $file) {
                if ($file->file_path) {
                    Storage::delete($file->file_path);
                }
                $file->delete();
            }
            
            return response()->json([
                'message' => 'File group deleted successfully',
                'count' => $groupFiles->count()
            ]);
        } else {
            // Delete just this file
            if ($standard->file_path) {
                Storage::delete($standard->file_path);
            }
            
            $standard->delete();
            
            return response()->json([
                'message' => 'File deleted successfully'
            ]);
        }
    }

    /**
     * Test file upload endpoint to diagnose issues
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testFileUpload(Request $request)
    {
        $response = [
            'request_method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'all_headers' => $request->headers->all(),
            'has_file' => $request->hasFile('file'),
            'all_fields' => $request->all(),
            'all_files' => $request->allFiles(),
        ];
        
        // Handle both array and single file uploads
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            
            // If it's a single file, convert to array for consistent handling
            if (!is_array($files)) {
                $files = [$files];
                $response['file_format'] = 'single';
            } else {
                $response['file_format'] = 'array';
            }
            
            $fileResults = [];
            
            foreach ($files as $index => $file) {
                $fileInfo = [
                    'index' => $index,
                    'valid' => $file->isValid(),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'client_mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'error' => $file->getError(),
                    'extension' => $file->getClientOriginalExtension(),
                    'path' => $file->path(),
                    'real_path' => $file->getRealPath(),
                ];
                
                // Try to store the file
                try {
                    $path = $file->store('test_uploads');
                    $fileInfo['stored_path'] = $path;
                    $fileInfo['store_success'] = true;
                } catch (\Exception $e) {
                    $fileInfo['store_success'] = false;
                    $fileInfo['store_error'] = $e->getMessage();
                }
                
                $fileResults[] = $fileInfo;
            }
            
            $response['files'] = $fileResults;
            $response['file_count'] = count($fileResults);
        } else {
            $response['file_format'] = 'none';
            $response['message'] = 'No files were uploaded';
        }
        
        return response()->json($response);
    }
}
