<?php

namespace App\Http\Controllers\Api; // Assuming you place it in app/Http/Controllers/Api

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Removed Response import as JsonResponse is used
use Illuminate\Support\Facades\Auth; // Keep Auth facade (though not used for login attempt here)
use Illuminate\Support\Facades\Hash; // Import Hash facade for password checking
use App\Models\User; // Import User model
use Illuminate\Validation\ValidationException; // Keep for potential future use or different error handling
use Illuminate\Http\JsonResponse; // Use this for type hinting

class AuthController extends Controller
{
	/**
	 * Handle an incoming authentication request using API Tokens.
	 *
	 * NOTE: This implementation uses API Token authentication.
	 * It finds the user, checks the password hash, and issues a Sanctum API token.
	 * This differs from SPA session-based authentication.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function login(Request $request): JsonResponse
	{
		// 1. Validate the incoming request data (including device_name for token)
		$request->validate([
			'email' => 'required|email',
			'password' => 'required',
			'device_name' => 'required|string', // Required to name the API token
		]);

		// 2. Attempt to find the user by email
		$user = User::where('email', $request->email)->first();

		// 3. Check if user exists and password is correct using Hash::check
		if (!$user || !Hash::check($request->password, $user->password)) {
			// Return a JSON response for invalid credentials
			return response()->json([
				'message' => __('auth.failed') // Use translation key
			], 401); // 401 Unauthorized
		}

		// 4. Optional: Revoke existing tokens for the same device name
		// $user->tokens()->where('name', $request->device_name)->delete();

		// 5. Create a new API token for the user
		// The 'device_name' helps identify the token later (e.g., 'mobile_app', 'user_browser')
		$token = $user->createToken($request->device_name)->plainTextToken;

		// 6. Return the token and user info (optional) in the response
		return response()->json([
			'message' => 'Login successful',
			'token' => $token,
			// Consider using an API Resource to control exposed user data
			'user' => $user
		], 200); // OK
	}

	/**
	 * Log the user out by revoking the current API token.
	 *
	 * NOTE: This assumes the request is authenticated via a Sanctum API token.
	 * It revokes the specific token used for the request.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function logout(Request $request): JsonResponse
	{
		// Ensure the user is authenticated via Sanctum token guard
		if (Auth::guard('sanctum')->check()) {
			// Revoke the token that was used to authenticate the current request
			$request->user()->currentAccessToken()->delete();
			return response()->json(['message' => 'Successfully logged out'], 200); // OK
		}

		// If not authenticated via token (shouldn't happen if middleware is applied correctly)
		return response()->json(['message' => 'Unauthenticated.'], 401); // Unauthorized
	}
}
