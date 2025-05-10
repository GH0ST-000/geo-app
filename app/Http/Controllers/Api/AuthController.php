<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->userService->register($request->all());

        return response()->json($result, 201, [], JSON_UNESCAPED_UNICODE);
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');
        $result = $this->userService->login($credentials);

        if (!$result) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function refresh(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // Add profile picture URLs to user data
        $userData = $user->toArray();
        $userData['profile_picture_url'] = $user->profile_picture_url;
        $userData['profile_thumbnail_url'] = $user->profile_thumbnail_url;
        $userData['profile_medium_url'] = $user->profile_medium_url;
        $userData['ulid'] = $user->ulid;

        // Include QR code only if user is active
        if ($user->is_verified) {
            $userData['qr_code'] = $user->qr_code;
        }

        return response()->json([
            'user' => $userData,
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function user(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // Add profile picture URLs to user data
        $userData = $user->toArray();
        $userData['profile_picture_url'] = $user->profile_picture_url;
        $userData['profile_thumbnail_url'] = $user->profile_thumbnail_url;
        $userData['profile_medium_url'] = $user->profile_medium_url;
        $userData['ulid'] = $user->ulid;

        // Include QR code only if user is active
        if ($user->is_verified) {
            $userData['qr_code'] = $user->qr_code;
        }

        return response()->json($userData, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function updatePassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|different:current_password',
            'confirm_password' => 'required|string|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $result = $this->userService->updatePassword(
            $user,
            $request->current_password,
            $request->new_password
        );

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 400);
        }

        return response()->json([
            'message' => $result['message']
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
