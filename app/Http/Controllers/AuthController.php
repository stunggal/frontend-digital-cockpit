<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Controller handling authentication views and API-backed login/logout.
     *
     * Notes:
     * - This controller delegates authentication to an external API defined
     *   by the `API_URL` environment variable.
     * - Access tokens returned from the API are stored in session under
     *   `api_token`. For production consider more secure storage.
     */
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        // Show the login form view
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        // Validate incoming request fields; this will redirect back on failure
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Prepare headers for calling the external auth API
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => '*/*',
        ];

        // Payload sent to the API
        $body = [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ];

        try {
            // Call the external authentication endpoint
            $response = Http::withHeaders($headers)->post(env('API_URL') . '/auth/login', $body);
            $data = $response->json();

            // On success, store returned access token in session and redirect
            if ($response->successful()) {
                // NOTE: consider encrypting session data / using secure storage
                Session::put('api_token', $data['access_token']);
                return redirect('/')->with('success', 'Selamat datang!');
            }

            // If API returned a readable error message, send it back to the form
            if ($response->failed() && isset($data['message'])) {
                return back()->withErrors([
                    'email' => $data['message'],
                ])->withInput($request->only('email'));
            }

            // Fallback error when API response is unexpected
            return back()->withErrors(['email' => 'Gagal login. Respons API tidak valid.'])->withInput();
        } catch (\Exception $e) {
            // Network/connection errors are caught here — show a user-friendly message
            return back()->withErrors(['email' => 'Gagal terhubung ke layanan otentikasi.'])->withInput();
        }
    }

    public function reset(Request $request)
    {
        // Show the password reset request view
        return view('auth.reset');
    }

    public function logout(Request $request)
    {
        // Remove API token from session to log the user out locally
        Session::forget('api_token');

        // Redirect to login with success message
        return redirect('/login')->with('success', 'Anda telah logout.');
    }
}
