<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Show the application dashboard with paginated medical checkup history.
     *
     * - Calls the external API endpoint `/get-data-medical-checkup-history` to
     *   retrieve the patient's historical medical checkups.
     * - Paginates the returned array manually using a `LengthAwarePaginator` so
     *   the view can render standard Laravel pagination links.
     *
     * Notes:
     * - `pasien_id` is currently hardcoded to '1' for demo purposes. Replace
     *   with the authenticated user's id or a request parameter in production.
     * - There are `dd()` calls used for quick debugging in development. They
     *   should be removed or replaced with proper logging before shipping.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */

    public function landing()
    {
        // Show the login form view
        return view('home.landing');
    }

    public function index()
    {
        // Prepare headers with bearer token stored in session
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . session('api_token'),
            'Accept' => '*/*',
        ];

        // TODO: replace with dynamic pasien id (e.g. from auth user)
        $body = [
            'pasien_id' => '1',
        ];

        $urlEndpoint = env('API_URL') . '/get-data-medical-checkup-history';

        try {
            $response = Http::withHeaders($headers)->post($urlEndpoint, $body);
            $data = $response->json();

            // Ensure we have an array to paginate; fallback to empty array
            $historyData = $data['medical_checkup_history'] ?? [];

            // Pagination configuration
            $perPage = 10;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();

            // Create a collection, slice out the items for the current page
            $currentItems = Collection::make($historyData)
                ->slice(($currentPage * $perPage) - $perPage, $perPage)
                ->all();

            // Build LengthAwarePaginator so views can use ->links() normally
            $medicalCheckupHistory = new LengthAwarePaginator(
                $currentItems,
                count($historyData),
                $perPage,
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );

            // Replace raw array with paginator for use in the view
            $data['medical_checkup_history'] = $medicalCheckupHistory;

            return view('home.index');

            // If API responds with a message, show it to the user
            if ($response->failed() && isset($data['message'])) {
                return back()->withErrors([
                    'message' => $data['message'],
                ])->withInput();
            }

            // Developer debug dump: remove in production
            dd('Unexpected API response while loading dashboard');

            return back()->withErrors(['message' => 'Gagal login. Respons API tidak valid.'])->withInput();
        } catch (\Exception $e) {
            // Development debug dump: replace with logging in production
            dd('Connection error: ' . $e->getMessage());
            return back()->withErrors(['message' => 'Gagal terhubung ke layanan otentikasi.'])->withInput();
        }
    }

    /**
     * Placeholder stub for getting heart rate.
     *
     * Currently returns a static value for development/testing. See
     * `PasienController::getHeartRate` for a full implementation that calls
     * the external API. Replace this stub with real logic as needed.
     *
     * @return string
     */
    public function getHeartRate()
    {
        // TODO: implement real API call or delegate to PasienController
        return '111';
    }

    /**
     * Placeholder stub for getting blood pressure.
     *
     * @return string
     */
    public function getBloodPressure()
    {
        // TODO: implement real API call
        return '111';
    }

    /**
     * Placeholder stub for getting SpO2 (oxygen saturation).
     *
     * @return string
     */
    public function getSpo2()
    {
        // TODO: implement real API call
        return '111';
    }

    /**
     * Simple consult page placeholder.
     *
     * @return string
     */
    public function consult()
    {
        // Placeholder route — replace with actual consult flow or view
        return 'consult page';
    }

    /**
     * Debug helper to inspect environment variable `API_URL_DARI_OS`.
     *
     * NOTE: This uses `dd()` and is intended for local debugging only.
     * @return void
     */
    public function testEnv()
    {
        dd(env('API_URL_DARI_OS'));
    }

    /**
     * Return the `model` view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function model()
    {
        return view('model');
    }

    /**
     * Return the `urat` view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function urat()
    {
        return view('urat');
    }

    /**
     * Return the `aaa` view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function aaa()
    {
        return view('aaa');
    }

    /**
     * Return the `setengah` view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function setengah()
    {
        return view('setengah');
    }

    /**
     * Return the `utuh` view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function utuh()
    {
        return view('utuh');
    }
}
