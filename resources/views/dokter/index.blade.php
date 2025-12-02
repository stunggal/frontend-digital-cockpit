@extends('layouts.master')
@section('title')
    Dashboard
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    {{--
        Dokter dashboard (jadwal)

        Expected data passed from controller:
        - $data['jadwal_hari_ini'] : array of schedule items for today

        Each $item is expected to contain:
        - 'jam' : time string (e.g. '14:00')
        - 'tanggal' : date string (e.g. '2025-12-02')
        - 'event' : event title
        - 'pasien' : array with keys 'avatar', 'nama', 'diagnosa', 'status'

        The view uses Carbon to format times/dates and displays a badge
        whose class depends on the pasien status. The modal at the bottom is
        a generic "Catatan" (notes) modal opened by each row's button.
    --}}
    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <h2 class="mb-1 fw-bold">Good Morning, dr. Susanti!</h2>
                            </div>
                        </div><!-- end card header -->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1 fw-bold">Jadwal Kegiatan</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                                        <thead class="table-light">
                                            <tr class="text-muted">
                                                <th scope="col">Waktu</th>
                                                <th scope="col" style="width: 20%;">Event</th>
                                                <th scope="col">Pasien</th>
                                                <th scope="col" style="width: 16%;">Diagnosa</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            {{-- Loop through today's schedule. Replace or guard the
                                                 loop with `@if (!empty($data['jadwal_hari_ini']))` if
                                                 the array can be missing. --}}
                                            @foreach ($data['jadwal_hari_ini'] as $item)
                                                <tr>
                                                    <td>
                                                        <h5 class="fs-14 my-1 fw-normal"><span class="text-muted">
                                                                {{ \Carbon\Carbon::parse($item['jam'])->format('H:i') }} -
                                                                {{ \Carbon\Carbon::parse($item['jam'])->addMinutes(20)->format('H:i') }}
                                                            </span></h5>
                                                        <span
                                                            class="text-muted">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}</span>
                                                    </td>
                                                    <td>{{ $item['event'] }}</td>
                                                    <td><img src="{{ $item['pasien']['avatar'] }}" alt=""
                                                            class="avatar-xs rounded-circle me-2">
                                                        <a href="#javascript: void(0);" class="text-body fw-semibold">
                                                            {{ $item['pasien']['nama'] }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $item['pasien']['diagnosa'] }}
                                                    </td>
                                                    <td>
                                                        {{-- Display pasien status with contextual badge styling.
                                                            You can centralize this mapping in a helper so the
                                                            view doesn't contain business rules. --}}
                                                        <h5 class="fs-14 my-1 fw-normal"><span
                                                                class="badge 
                                                                @if ($item['pasien']['status'] == 'Moderate') bg-warning-subtle text-warning
                                                                @elseif($item['pasien']['status'] == 'Stable')
                                                                    bg-success-subtle text-success
                                                                @elseif($item['pasien']['status'] == 'Critical')
                                                                    bg-danger-subtle text-danger
                                                                @elseif($item['pasien']['status'] == 'Danger')
                                                                    bg-danger-subtle text-danger 
                                                                @elseif($item['pasien']['status'] == 'Improving')
                                                                    bg-success-subtle text-success
                                                                @elseif($item['pasien']['status'] == 'Worsening')
                                                                    bg-danger-subtle text-danger @endif
                                                                ">
                                                                {{ $item['pasien']['status'] }}
                                                            </span>
                                                        </h5>
                                                    </td>
                                                    <td>
                                                        {{-- Open the notes modal. If you need per-row notes,
                                                            consider passing the item id into the modal via
                                                            data-* attributes and updating modal content via JS. --}}
                                                        <button type="button" class="btn btn-primary"
                                                            data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
                                                            Catatan
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody><!-- end tbody -->
                                    </table><!-- end table -->
                                </div><!-- end table responsive -->
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div><!-- end row -->
            </div> <!-- end .h-100-->

        </div> <!-- end col -->
    </div>
    <div class="modal fade" id="exampleModalgrid" tabindex="-1" aria-labelledby="exampleModalgridLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="exampleModalgridLabel">Catatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-xxl-12">
                                <div>
                                    <label for="firstName" class="form-label">Catatan Umum</label>
                                    <textarea class="form-control" id="notes" rows="3" placeholder="Masukkan catatan umum" name="catatan"></textarea>
                                </div>
                            </div><!--end col-->
                        </div><!--end row-->
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <!-- dashboard init -->
    <script src="{{ URL::asset('build/js/pages/dashboard-ecommerce.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
