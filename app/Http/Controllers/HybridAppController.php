<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HybridAppController extends Controller
{
    // index
    public function index()
    {

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . session('api_token'),
            'Accept' => '*/*',
        ];
        $urlEndpoint = env('API_URL') . '/get-pasien-list';
        $response = Http::withHeaders($headers)->post($urlEndpoint);
        $data = $response->json();

        dd($data);
        return view('hybrid.index', compact('data'));
    }
}
