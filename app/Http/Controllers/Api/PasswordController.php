<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    /**
     * Generate a random password and send it to the specified email
     * Also updates the user's password in the database
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * Note: Ensure that email configuration is set up in .env with:
     * MAIL_MAILER=smtp
     * MAIL_HOST=your_smtp_host
     * MAIL_PORT=your_smtp_port
     * MAIL_USERNAME=your_username
     * MAIL_PASSWORD=your_password
     * MAIL_ENCRYPTION=tls
     * MAIL_FROM_ADDRESS=your_from_email
     * MAIL_FROM_NAME="${APP_NAME}"
     * 
     * The API checks the Accept-Language header to determine which language to use:
     * - 'ka' for Georgian
     * - 'en' for English (default if not specified)
     */
    public function generateAndSendPassword(Request $request)
    {
        // Validate the email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find user by email
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'message' => 'User not found with the provided email'
            ], 404);
        }

        // Generate a random 8-character password
        $password = Str::random(8);
        
        // Update the user's password in the database
        $user->password = Hash::make($password);
        $user->save();
        
        // Determine language preference from header (default to English if not specified)
        $language = $request->header('Accept-Language', 'en');
        
        // Only use 'ka' or 'en' - default to 'en' for any other values
        $language = in_array($language, ['ka', 'en']) ? $language : 'en';
        
        // Email subject based on language
        $subjects = [
            'en' => 'Your Generated Password',
            'ka' => 'თქვენი დაგენერირებული პაროლი'
        ];
        
        // Set the template name based on language
        $template = "emails.generated_password_{$language}";
        
        // Send the email
        try {
            Mail::send($template, ['password' => $password], function ($message) use ($request, $subjects, $language) {
                $message->to($request->email)
                    ->subject($subjects[$language]);
            });
            
            return response()->json([
                'message' => 'Password generated, updated, and sent successfully',
                'email' => $request->email,
                'language' => $language
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send password email',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 