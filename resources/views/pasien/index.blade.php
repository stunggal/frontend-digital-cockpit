@extends('layouts.master')
@section('title')
    @lang('translation.crm')
@endsection
@section('content')
    {{--
                Pasien listing view

                - Expects `$data` to be an iterable array of pasien records.
                - Each `$pasien` should contain keys:
                    - 'avatar', 'nama', 'tanggal_lahir', 'jenis_kelamin', 'no_telp', 'diagnosa',
                        and 'random_dokter' (with 'avatar' and 'nama').
                - The search form issues a GET to `pasien.index`; the query parameter
                    name used in the template is `pasien` (adjust controller/query logic
                    if you expect `search`).
                - Inline comments highlight where to adapt gender or age logic.
        --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Pasien</h4>
                    <div class="flex-shrink-0">
                        <div class="col-sm">
                            <div class="d-flex justify-content-sm-end">
                                <div class="search-box ms-2">
                                    {{-- Search form
                                        - This uses `name="pasien"` for the input; update the controller
                                          to read `request('pasien')` or change the input name to
                                          `search` for consistency with `request()->query('search')`.
                                    --}}
                                    <form action="{{ route('pasien.index') }}" method="GET">
                                        <div class="position-relative">
                                            <input type="text" class="form-control" name="pasien"
                                                value="{{ request()->query('search') }}" placeholder="Search...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-muted">
                                    <th scope="col" style="width: 2%;"></th>
                                    <th scope="col">Name</th>
                                    <th scope="col" style="width: 20%;">Contact Info</th>
                                    <th scope="col">Diagnose</th>
                                    <th scope="col" style="width: 16%;">Handle By</th>
                                    <th scope="col" style="width: 12%;">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                {{-- Iterate pasien records provided by the controller --}}
                                @foreach ($data as $pasien)
                                    <tr>
                                        <td>
                                            <img src="{{ $pasien['avatar'] }}" alt=""
                                                class="avatar-xs rounded-circle me-2">
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">
                                                {{ $pasien['nama'] }}
                                            </h5>
                                            {{--
                                                Compute age from `tanggal_lahir` and map gender code:
                                                - `tanggal_lahir` expected in YYYY-MM-DD format
                                                - `jenis_kelamin` uses 'L' for male and other values for female
                                                Update this logic if your backend uses different codes.
                                            --}}
                                            <span class="text-muted">
                                                {{ \Carbon\Carbon::parse($pasien['tanggal_lahir'])->age }} Y,
                                                {{ $pasien['jenis_kelamin'] == 'L' ? 'Male' : 'Female' }}
                                            </span>
                                        </td>
                                        <td>{{ $pasien['no_telp'] }}</td>
                                        <td><span
                                                class="badge bg-info-subtle text-info p-2">{{ $pasien['diagnosa'] }}</span>
                                        </td>
                                        <td><img src="{{ $pasien['random_dokter']['avatar'] }}" alt=""
                                                class="avatar-xs rounded-circle me-2">
                                            <a href="#javascript: void(0);" class="text-body fw-semibold">
                                                {{ $pasien['random_dokter']['nama'] }}
                                            </a>
                                        </td>
                                        <td>
                                            {{--
                                                Action buttons:
                                                - The view currently has a placeholder `eye` icon. Replace
                                                  the `href` with `route('pasien.view', $pasien['id'])` or
                                                  similar to link to a pasien detail page.
                                                - Add edit/delete actions as required.
                                            --}}
                                            <a href="{{ route('pasien.view', ['id' => $pasien['id'] ?? '']) }}"
                                                class="action-icon">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            {{-- TODO: add edit/delete links and permission checks --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody><!-- end tbody -->
                        </table><!-- end table -->
                    </div><!-- end table responsive -->
                    <div class="d-flex justify-content-center mt-3">
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection
@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-crm.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
