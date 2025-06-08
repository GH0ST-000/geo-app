<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Auth::user()->products()
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->get();

        $products->each(function ($product) {
            $product->product_images = $product->getProductImagesAttribute();
            $product->standard_files = $product->getStandardFilesAttribute();
        });

        return response()->json($products, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string',
            'packing_capacity' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'product_images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:20480',
            'product_file.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:20480',
            'standard' => 'sometimes|required|string|in:' . implode(',', $this->validStandardTypes),
            'standard_files.*' => 'sometimes|file|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = new Product();
        $product->user_id = Auth::id();
        $product->product_name = $request->product_name;
        $product->product_description = $request->product_description;
        $product->packing_capacity = $request->packing_capacity;
        $product->address = $request->address;
        
        // Handle standard data if provided
        if ($request->has('standard')) {
            $product->standard = $request->standard;
            
            // Generate a group ID for standard files
            $product->standard_group_id = (string) Str::uuid();
        }
        
        $product->save();

        // Handle product images from product_images field if any
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                if ($image->isValid()) {
                    $product->addMedia($image)
                        ->toMediaCollection('product_images');
                }
            }
        }

        // Handle product images from product_file field if any
        if ($request->hasFile('product_file')) {
            foreach ($request->file('product_file') as $image) {
                if ($image->isValid()) {
                    $product->addMedia($image)
                        ->toMediaCollection('product_images');
                }
            }
        }
        
        // Handle standard files if any
        if ($request->hasFile('standard_files')) {
            foreach ($request->file('standard_files') as $file) {
                if ($file->isValid()) {
                    // Get file details for custom properties
                    $extension = strtolower($file->getClientOriginalExtension());
                    $fileType = $file->getClientMimeType() ?: 'application/octet-stream';
                    $fileCategory = $this->fileTypeMap[$extension] ?? 'binary';
                    
                    $product->addMedia($file)
                        ->withCustomProperties([
                            'file_category' => $fileCategory,
                            'original_extension' => $extension,
                            'mime_type' => $fileType
                        ])
                        ->toMediaCollection('standard_files');
                }
            }
        }

        // Load images URLs
        $product->product_images = $product->getProductImagesAttribute();
        $product->standard_files = $product->getStandardFilesAttribute();

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('media')->findOrFail($id);

        // Check if the current user owns this product
        if ($product->user_id != Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to view this product'
            ], 403);
        }

        // Load images URLs
        $product->product_images = $product->getProductImagesAttribute();
        $product->standard_files = $product->getStandardFilesAttribute();

        return response()->json($product, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request Request data
     * @param string $id Product ID
     * @return \Illuminate\Http\JsonResponse
     *
     * Using POST method to support form data with file uploads
     * Image handling:
     * - Files sent as 'product_images[]' or 'product_file[]' will ALWAYS be ADDED (not replaced)
     * - Same keys are used in both create and update methods
     * - To delete images: Send an array of media IDs in 'delete_images[]'
     * - To delete a single image: Use the dedicated endpoint DELETE /products/{productId}/images/{imageId}
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        // Check if the current user owns this product
        if ($product->user_id != Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to update this product'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'product_name' => 'sometimes|required|string|max:255',
            'product_description' => 'sometimes|required|string',
            'packing_capacity' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'product_images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:20480',
            'product_file.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:20480',
            'is_active' => 'sometimes|boolean',
            'standard' => 'sometimes|string|in:' . implode(',', $this->validStandardTypes),
            'standard_files.*' => 'sometimes|file|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update product fields
        if ($request->has('product_name')) {
            $product->product_name = $request->product_name;
        }

        if ($request->has('product_description')) {
            $product->product_description = $request->product_description;
        }

        if ($request->has('packing_capacity')) {
            $product->packing_capacity = $request->packing_capacity;
        }

        if ($request->has('address')) {
            $product->address = $request->address;
        }

        if ($request->has('is_active')) {
            $product->is_active = $request->is_active;
        }
        
        // Update standard if provided
        if ($request->has('standard')) {
            $product->standard = $request->standard;
            
            // Generate a new group ID if none exists
            if (!$product->standard_group_id) {
                $product->standard_group_id = (string) Str::uuid();
            }
        }

        $product->save();

        // Handle product images if any
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                if ($image->isValid()) {
                    $product->addMedia($image)
                        ->toMediaCollection('product_images');
                }
            }
        }

        // Handle product images from product_file field if any
        if ($request->hasFile('product_file')) {
            foreach ($request->file('product_file') as $image) {
                if ($image->isValid()) {
                    $product->addMedia($image)
                        ->toMediaCollection('product_images');
                }
            }
        }
        
        // Handle standard files if any
        if ($request->hasFile('standard_files')) {
            foreach ($request->file('standard_files') as $file) {
                if ($file->isValid()) {
                    // Get file details for custom properties
                    $extension = strtolower($file->getClientOriginalExtension());
                    $fileType = $file->getClientMimeType() ?: 'application/octet-stream';
                    $fileCategory = $this->fileTypeMap[$extension] ?? 'binary';
                    
                    $product->addMedia($file)
                        ->withCustomProperties([
                            'file_category' => $fileCategory,
                            'original_extension' => $extension,
                            'mime_type' => $fileType
                        ])
                        ->toMediaCollection('standard_files');
                }
            }
        }

        // Handle deleted images
        if ($request->has('delete_images') && is_array($request->delete_images)) {
            foreach ($request->delete_images as $mediaId) {
                $media = $product->media()->where('collection_name', 'product_images')->find($mediaId);
                if ($media) {
                    $media->delete();
                }
            }
        }
        
        // Handle deleted standard files
        if ($request->has('delete_standard_files') && is_array($request->delete_standard_files)) {
            foreach ($request->delete_standard_files as $mediaId) {
                $media = $product->media()->where('collection_name', 'standard_files')->find($mediaId);
                if ($media) {
                    $media->delete();
                }
            }
        }

        // Load fresh media
        $product->load('media');

        // Load images URLs
        $product->product_images = $product->getProductImagesAttribute();
        $product->standard_files = $product->getStandardFilesAttribute();

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Check if the current user owns this product
        if ($product->user_id != Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to delete this product'
            ], 403);
        }

        try {
            // Begin a database transaction
            DB::beginTransaction();
            
            // Delete all associated media files
            $product->clearMediaCollection('product_images');
            $product->clearMediaCollection('standard_files');
            
            // Delete the product
            $product->delete();
            
            // Commit the transaction
            DB::commit();
            
            return response()->json([
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            
            return response()->json([
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all products (public endpoint)
     */
    public function getAllProducts(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $query = Product::with('media')
            ->where('is_active', true);

        // Filter by search term if provided
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('product_name', 'like', "%{$searchTerm}%")
                  ->orWhere('product_description', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%");
            });
        }
        
        // Filter by standard if provided
        if ($request->has('standard') && in_array($request->standard, $this->validStandardTypes)) {
            $query->where('standard', $request->standard);
        }

        $products = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Transform products to include image URLs
        $products->getCollection()->transform(function ($product) {
            $product->product_images = $product->getProductImagesAttribute();
            $product->standard_files = $product->getStandardFilesAttribute();
            $product->user = [
                'ulid' => $product->user->ulid,
                'first_name' => $product->user->first_name,
                'last_name' => $product->user->last_name,
                'profile_picture_url' => $product->user->profile_picture_url,
            ];
            return $product;
        });

        return response()->json($products, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get a specific product (public endpoint)
     */
    public function getProduct(string $id)
    {
        $product = Product::with(['media', 'user'])
            ->where('is_active', true)
            ->findOrFail($id);

        // Load images URLs
        $product->product_images = $product->getProductImagesAttribute();
        $product->standard_files = $product->getStandardFilesAttribute();

        // Transform user data
        $product->user = [
            'ulid' => $product->user->ulid,
            'first_name' => $product->user->first_name,
            'last_name' => $product->user->last_name,
            'profile_picture_url' => $product->user->profile_picture_url,
        ];

        return response()->json($product, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Delete a single product image
     */
    public function deleteImage(Request $request, string $productId, string $imageId)
    {
        $product = Product::findOrFail($productId);

        // Check if the current user owns this product
        if ($product->user_id != Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to modify this product'
            ], 403);
        }

        // Find and delete the image
        $media = $product->media()->where('collection_name', 'product_images')->find($imageId);

        if (!$media) {
            return response()->json([
                'message' => 'Image not found'
            ], 404);
        }

        $media->delete();

        // Reload the product with fresh images
        $product->load('media');
        $product->product_images = $product->getProductImagesAttribute();
        $product->standard_files = $product->getStandardFilesAttribute();

        return response()->json([
            'message' => 'Image deleted successfully',
            'product' => $product
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Delete a single standard file
     */
    public function deleteStandardFile(Request $request, string $productId, string $fileId)
    {
        $product = Product::findOrFail($productId);

        // Check if the current user owns this product
        if ($product->user_id != Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to modify this product'
            ], 403);
        }

        // Find and delete the file
        $media = $product->media()->where('collection_name', 'standard_files')->find($fileId);

        if (!$media) {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }

        $media->delete();

        // Reload the product with fresh files
        $product->load('media');
        $product->product_images = $product->getProductImagesAttribute();
        $product->standard_files = $product->getStandardFilesAttribute();

        return response()->json([
            'message' => 'File deleted successfully',
            'product' => $product
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get products for a specific user (public endpoint)
     *
     * @param string $ulid User's ULID
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserProducts(string $ulid, Request $request)
    {
        $perPage = $request->get('per_page', 10);

        // Find the user by ULID first
        $user = User::where('ulid', $ulid)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Get the preferred language from Accept-Language header, default to 'ka'
        $language = $request->header('Accept-Language', 'ka');

        // If language is not 'ka' or 'en', default to 'ka'
        if ($language !== 'ka' && $language !== 'en') {
            $language = 'ka';
        }

        // Get user's standards from UserStandard table
        $userStandards = $user->standards()
            ->where('is_verified', true)
            ->get(['group_id', 'slug']);

        // Get unique standards by group_id
        $uniqueStandards = collect();
        $seenGroupIds = [];

        foreach ($userStandards as $standard) {
            if (!in_array($standard->group_id, $seenGroupIds)) {
                $uniqueStandards->push($standard);
                $seenGroupIds[] = $standard->group_id;
            }
        }

        $standardSlugs = $uniqueStandards->pluck('slug')->toArray();

        // Standard translations
        $standardTranslations = [
            'ka' => [
                'honey_standard' => 'თაფლის სტანდარტი',
                'dairy_standard' => 'რძის სტანდარტი',
                'crop_standard' => 'მემცენარეობის სტანდარტი',
                'other_standard' => 'სხვა სტანდარტი',
                'images' => 'სურათები',
                'documents' => 'დოკუმენტები',
                'videos' => 'ვიდეოები',
                'audio' => 'აუდიო',
                'spreadsheets' => 'ცხრილები',
                'binary' => 'ბინარული ფაილები'
            ],
            'en' => [
                'honey_standard' => 'Honey Standard',
                'dairy_standard' => 'Dairy Standard',
                'crop_standard' => 'Crop Standard',
                'other_standard' => 'Other Standard',
                'images' => 'Images',
                'documents' => 'Documents',
                'videos' => 'Videos',
                'audio' => 'Audio',
                'spreadsheets' => 'Spreadsheets',
                'binary' => 'Binary Files'
            ]
        ];

        // Translate slugs to names based on language
        $standardNames = [];
        foreach ($standardSlugs as $slug) {
            if (isset($standardTranslations[$language][$slug])) {
                $standardNames[] = $standardTranslations[$language][$slug];
            }
        }

        $query = Product::with(['media', 'user'])
            ->where('user_id', $user->id)
            ->where('is_active', true);
            
        // Filter by standard if provided
        if ($request->has('standard') && in_array($request->standard, $this->validStandardTypes)) {
            $query->where('standard', $request->standard);
        }
            
        $query->orderBy('created_at', 'desc');

        $products = $query->paginate($perPage);

        // Transform products to include image URLs
        $transformedProducts = $products->getCollection()->map(function ($product) {
            $productArray = $product->toArray();
            $productArray['product_images'] = $product->getProductImagesAttribute();
            $productArray['standard_files'] = $product->getStandardFilesAttribute();

            // Include basic user info
            if ($product->user) {
                $productArray['user'] = [
                    'ulid' => $product->user->ulid,
                    'first_name' => $product->user->first_name,
                    'last_name' => $product->user->last_name,
                    'qr_code' => $product->user->is_verified ? $product->user->qr_code : null,
                    'profile_picture_url' => $product->user->profile_picture_url,
                ];
            }

            // Remove the media collection to keep response clean
            unset($productArray['media']);

            return $productArray;
        });

        // Create a new paginator with transformed products
        $paginatedProducts = new \Illuminate\Pagination\LengthAwarePaginator(
            $transformedProducts,
            $products->total(),
            $products->perPage(),
            $products->currentPage(),
            [
                'path' => \Illuminate\Support\Facades\Request::url(),
                'query' => $request->query(),
            ]
        );

        // Add standards to the response
        $responseData = $paginatedProducts->toArray();
        $responseData['standards'] = $standardNames;

        return response()->json($responseData, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Upload standard files to an existing product
     *
     * @param Request $request
     * @param string $id Product ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadStandardFiles(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        // Check if the current user owns this product
        if ($product->user_id != Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to modify this product'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'standard' => 'sometimes|required|string|in:' . implode(',', $this->validStandardTypes),
            'standard_files' => 'required|array',
            'standard_files.*' => 'required|file|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Debug information
        $requestInfo = [
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'has_file' => $request->hasFile('standard_files'),
            'all_fields' => array_keys($request->all()),
        ];

        // Update standard type if provided
        if ($request->has('standard')) {
            $product->standard = $request->standard;
            
            // Generate a new group ID if none exists
            if (!$product->standard_group_id) {
                $product->standard_group_id = (string) Str::uuid();
            }
            
            $product->save();
        } elseif (!$product->standard) {
            // If no standard is set and none is provided, use a default
            $product->standard = 'documents';
            
            // Generate a new group ID if none exists
            if (!$product->standard_group_id) {
                $product->standard_group_id = (string) Str::uuid();
            }
            
            $product->save();
        }

        // Process each uploaded file
        $uploadedFiles = [];
        $failedFiles = [];
        
        if ($request->hasFile('standard_files')) {
            foreach ($request->file('standard_files') as $file) {
                if ($file->isValid()) {
                    try {
                        // Get file details for custom properties
                        $extension = strtolower($file->getClientOriginalExtension());
                        $fileType = $file->getClientMimeType() ?: 'application/octet-stream';
                        $fileCategory = $this->fileTypeMap[$extension] ?? 'binary';
                        $originalName = $file->getClientOriginalName();
                        
                        $media = $product->addMedia($file)
                            ->withCustomProperties([
                                'file_category' => $fileCategory,
                                'original_extension' => $extension,
                                'mime_type' => $fileType,
                                'original_name' => $originalName
                            ])
                            ->toMediaCollection('standard_files');
                            
                        $uploadedFiles[] = [
                            'id' => $media->id,
                            'name' => $media->name,
                            'file_name' => $media->file_name,
                            'mime_type' => $media->mime_type,
                            'size' => $media->size,
                            'url' => $media->getUrl(),
                            'file_category' => $fileCategory
                        ];
                    } catch (\Exception $e) {
                        $failedFiles[] = [
                            'name' => $file->getClientOriginalName(),
                            'error' => $e->getMessage()
                        ];
                    }
                } else {
                    $failedFiles[] = [
                        'name' => $file->getClientOriginalName(),
                        'error' => 'Invalid file'
                    ];
                }
            }
        }

        // Reload the product with fresh files
        $product->load('media');
        $product->product_images = $product->getProductImagesAttribute();
        $product->standard_files = $product->getStandardFilesAttribute();

        $response = [
            'message' => count($uploadedFiles) . ' files uploaded successfully',
            'product' => $product,
            'uploaded_files' => $uploadedFiles
        ];
        
        if (!empty($failedFiles)) {
            $response['failed_files'] = $failedFiles;
        }

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get product files for display (public endpoint, no auth required)
     *
     * @param string $id Product ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductFilesForDisplay(string $id)
    {
        try {
            $product = Product::with('media')->findOrFail($id);
            
            // Create a simplified response with just the files data
            $response = [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'product_images' => $product->getProductImagesAttribute(),
                'standard_files' => $product->getStandardFilesAttribute(),
            ];
            
            return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Product not found or error retrieving files',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
