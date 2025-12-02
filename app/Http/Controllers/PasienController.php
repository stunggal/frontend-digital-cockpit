<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Controller to manage patient (pasien) related API interactions.
 *
 * Each method below calls an external API defined by the `API_URL` environment
 * variable. Responses are returned as JSON when successful, otherwise the
 * controller either redirects back with validation/errors or returns error
 * information as JSON.
 */
class PasienController extends Controller
{
    /**
     * Get heart rate data for the (currently hardcoded) user.
     *
     * - Builds headers including bearer token from session
     * - Calls external API `/get-heart-rate`
     * - Returns JSON on success or redirects back with errors on failure
     *
     * @param Request $request Incoming HTTP request (not used currently)
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getHeartRate(Request $request)
    {
        // Request headers: content type + authorization retrieved from session
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . session('api_token'),
            'Accept' => '*/*',
        ];

        // Body payload: currently a fixed user id (replace with dynamic value)
        $body = [
            'user_id' => '1',
        ];

        // Build endpoint URL from env config
        $urlEndpoint = env('API_URL') . '/get-heart-rate';

        try {
            $response = Http::withHeaders($headers)->post($urlEndpoint, $body);
            $data = $response->json();
            if ($response->successful()) {
                return response()->json($data);
            }

            // If API returned an error message, send it back to the form
            if ($response->failed() && isset($data['message'])) {
                return back()->withErrors([
                    'message' => $data['message'],
                ])->withInput();
            }
        } catch (\Exception $e) {
            // Catch network/other errors and return to previous page with message
            return back()->withErrors([
                'message' => $e->getMessage(),
            ])->withInput();
        }
    }

    /**
     * Get blood pressure for the user.
     *
     * This method mirrors the structure of `getHeartRate` but calls
     * `/get-blood-pressure` endpoint.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getBloodPressure(Request $request)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . session('api_token'),
            'Accept' => '*/*',
        ];

        $body = [
            'user_id' => '1',
        ];

        $urlEndpoint = env('API_URL') . '/get-blood-pressure';
        $response = Http::withHeaders($headers)->post($urlEndpoint, $body);
        $data = $response->json();

        if ($response->successful()) {
            return response()->json($data);
        }

        if ($response->failed() && isset($data['message'])) {
            return back()->withErrors([
                'message' => $data['message'],
            ])->withInput();
        }
    }

    /**
     * Get SpO2 (oxygen saturation) for the user.
     *
     * Same pattern as the previous methods: build headers, call
     * `/get-spo2`, return JSON on success.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getSpo2(Request $request)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . session('api_token'),
            'Accept' => '*/*',
        ];

        $body = [
            'user_id' => '1',
        ];

        $urlEndpoint = env('API_URL') . '/get-spo2';
        $response = Http::withHeaders($headers)->post($urlEndpoint, $body);
        $data = $response->json();

        if ($response->successful()) {
            return response()->json($data);
        }

        if ($response->failed() && isset($data['message'])) {
            return back()->withErrors([
                'message' => $data['message'],
            ])->withInput();
        }
    }

    /**
     * Perform a consultation by sending patient data to an external webhook.
     *
     * This method constructs a simple HS256 JWT manually (no external
     * dependency) and sends patient information to the configured webhook.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function consult(Request $request)
    {
        try {
            // --- 1. Prepare JWT ---
            // NOTE: keep secret keys in env or vault for production
            $secretKey = 'stglSTGL1234';
            $issuedAt = time();
            $expirationTime = $issuedAt + 3600; // token valid for 1 hour
            $payload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
            ];

            // Helper: base64 url encode (RFC 7515 style)
            $base64UrlEncode = function ($data) {
                return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
            };

            // Create JWT header/payload, sign with HMAC-SHA256
            $header = ['typ' => 'JWT', 'alg' => 'HS256'];
            $headerEncoded = $base64UrlEncode(json_encode($header));
            $payloadEncoded = $base64UrlEncode(json_encode($payload));
            $unsignedToken = $headerEncoded . '.' . $payloadEncoded;
            $signature = hash_hmac('sha256', $unsignedToken, $secretKey, true);
            $signatureEncoded = $base64UrlEncode($signature);
            $jwt = $unsignedToken . '.' . $signatureEncoded;

            // Example body data — replace with dynamic data from $request as needed
            $bodyData = [
                "pasienInfo" => [
                    "umur" => 58,
                    "jenisKelamin" => "Female",
                    "diagnosa" => "Jantung Koroner",
                    "deskripsi" => "Pasien perempuan, 58 tahun, datang dengan keluhan nyeri dada kiri menjalar ke lengan kiri sejak 3 bulan terakhir, terutama saat aktivitas fisik sedang hingga berat. Nyeri bersifat menekan, durasi ±10 menit, membaik dengan istirahat. Tidak disertai keringat dingin atau sesak napas berat. Hasil EKG menunjukkan iskemia di dinding anterior. Ekokardiografi menunjukkan fungsi pompa jantung dalam batas normal (EF 55%) tanpa kelainan katup. CT angiografi koroner menunjukkan stenosis >70% pada arteri LAD proksimal dan 50% pada RCA. Pasien telah menjalani PCI dengan pemasangan stent di LAD 2 bulan lalu. Kondisi pasca-PCI stabil, tanpa keluhan angina saat kontrol terakhir."
                ],
                "statistikMedis" => [
                    "konsultasiSelesai" => 26,
                    "prosedurBedah" => 4
                ],
                "tandaVital" => [
                    "glukosa" => "85 mg/dL",
                    "suhuTubuh" => "36°C",
                    "beratBadan" => "75 kg",
                    "detakJantung" => "98.7 Bpm",
                    "tekananDarah" => "109/72 mmHg",
                    "saturasiOksigen" => "91%",
                    "durasiTidurTadiMalam" => "3.8 H"
                ],
            ];

            $apiUrl = 'https://myn8n.stunggal.id/webhook/b64d54c4-a60a-44f7-bb6d-c845bc234081';

            // Send request with generated JWT as bearer token
            $response = Http::withToken($jwt)
                ->timeout(30)
                ->get($apiUrl, $bodyData);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Konsultasi berhasil diterima oleh API.',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'API merespons dengan error.',
                    'status_code' => $response->status(),
                    'error_details' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi error internal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a patient's detail view along with past schedules and available
     * schedules. All data is retrieved from the external API.
     *
     * @param mixed $id Pasien identifier
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . session('api_token'),
            'Accept' => '*/*',
        ];

        // Send pasien_id in the body when requesting pasien-related endpoints
        $body = [
            'pasien_id' => $id,
        ];

        $urlEndpoint = env('API_URL') . '/get-pasien';
        $urlEndpointJadwal = env('API_URL') . '/get-jadwal-pasien-past';
        $urlJadwal = env('API_URL') . '/get-jadwal';

        $response = Http::withHeaders($headers)->post($urlEndpoint, $body);
        $data = $response->json();

        $responseJadwal = Http::withHeaders($headers)->post($urlEndpointJadwal, $body);
        $dataJadwal = $responseJadwal->json();

        $responseJadwal2 = Http::withHeaders($headers)->post($urlJadwal, $body);
        $jadwal = $responseJadwal2->json();

        return view('pasien.view', compact('data', 'dataJadwal', 'jadwal'));
    }

    /**
     * Display a listing of pasien retrieved from the external API.
     *
     * @return \Illuminate\View\View
     */
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

        return view('pasien.index', compact('data'));
    }

    /**
     * Get food recommendation based on provided description using LLM API.
     *
     * Accepts a `description` in the request body and forwards it to the
     * `/llm-recom-food` endpoint. Returns JSON results or redirects back
     * with errors.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getFoodRecommendation(Request $request)
    {
        $description = $request->input('description');

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . session('api_token'),
            'Accept' => '*/*',
        ];

        $body = [
            'description' => $description,
        ];

        $urlEndpoint = env('API_URL') . '/llm-recom-food';

        try {
            $response = Http::withHeaders($headers)->post($urlEndpoint, $body);
            $data = $response->json();
            if ($response->successful()) {
                return response()->json($data);
            }
            if ($response->failed() && isset($data['message'])) {
                return back()->withErrors([
                    'message' => $data['message'],
                ])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors([
                'message' => $e->getMessage(),
            ])->withInput();
        }
    }
}
