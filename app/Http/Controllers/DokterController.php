<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DokterController extends Controller
{
    /**
     * Dokter index - show doctor's schedule (jadwal).
     *
     * - Calls the external API `/get-jadwal-dokter` using the session token.
     * - Expects an array/structure in the API response that can be passed to
     *   the `dokter.index` view as `$data`.
     *
     * Notes:
     * - `dokter_id` is currently hardcoded to '1'. Replace with the
     *   authenticated doctor's id or a request parameter in production.
     * - Remove or replace debug helpers (e.g. `dd()`) when moving to staging.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Prepare common headers for the API call
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . session('api_token'),
            'Accept' => '*/*',
        ];

        // TODO: replace hardcoded dokter_id with dynamic value
        $body = [
            'dokter_id' => '1',
        ];

        // Build endpoint and call external API
        $urlEndpoint = env('API_URL') . '/get-jadwal-dokter';
        $response = Http::withHeaders($headers)->post($urlEndpoint, $body);
        $data = $response->json();

        if ($response->successful()) {
            // Successful: pass data to the view
            return view('dokter.index', compact('data'));
        }

        // If the API failed and returned a message, show it to the user
        if ($response->failed() && isset($data['message'])) {
            return back()->withErrors([
                'message' => $data['message'],
            ])->withInput();
        }
    }
}
