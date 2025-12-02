@extends('layouts.master')
@section('title')
    @lang('translation.profile')
@endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}">
@endsection
@section('content')
    {{--
        Pasien detail view

        Variables expected from controller:
        - `$data` : associative array with pasien details (keys used below: 'avatar', 'nama',
            'tanggal_lahir', 'diagnosa', 'alamat', 'no_telp', 'tgl_masuk', 'deskripsi')
        - `$dataJadwal` : iterable list of past schedule/events for the patient
        - `$jadwal` : iterable list of upcoming schedules

        Notes for maintainers:
        - Several places use `rand()` to show demo values (e.g. consultations, procedures,
          vitals). Replace these with real API/backend values as needed.
        - Date formatting uses Carbon; ensure input date strings are parseable.
    --}}
    <div class="profile-foreground position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg">
            <img src="{{ URL::asset('build/images/profile-bg.jpg') }}
            " alt="" class="profile-wid-img" />
        </div>
    </div>
    <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
        <div class="row g-4">
            <div class="col-auto">
                <div class="avatar-lg">
                    <img src="{{ URL::asset($data['avatar']) }}" alt="" class="img-thumbnail rounded-circle" />
                </div>
            </div>
            <!--end col-->
            <div class="col">
                <div class="p-2">
                    {{-- Patient header: name, age computed from `tanggal_lahir`, and diagnosis --}}
                    <h3 class="text-white mb-1">{{ $data['nama'] }}</h3>
                    <p class="text-white text-opacity-75">{{ \Carbon\Carbon::parse($data['tanggal_lahir'])->age }}
                        Y
                        | {{ $data['diagnosa'] }}</p>
                    <div class="hstack text-white-50 gap-1">
                        <div class="me-2"><i
                                class="ri-map-pin-user-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>{{ $data['alamat'] }}
                            <div>
                                <i class="ri-building-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>Main
                                Hospital
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
                <div class="col-12 col-lg-auto order-last order-lg-0">
                    <div class="row text text-white-50 text-center">
                        <div class="col-lg-6 col-4">
                            <div class="p-2">
                                {{-- Demo counters: replace `rand()` with real counters from backend --}}
                                <h4 class="text-white mb-1">{{ rand(1, 50) }}</h4>
                                <p class="fs-15 mb-0">Consultations</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-4">
                            <div class="p-2">
                                <h4 class="text-white mb-1">{{ rand(1, 5) }}</h4>
                                <p class="fs-15 mb-0">Surgical Procedures</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->

            </div>
            <!--end row-->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="d-flex profile-wrapper">
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                    <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                        class="d-none d-md-inline-block">Overview</span>
                                </a>
                            </li>
                        </ul>
                        {{-- <div class="flex-shrink-0">
                        <a href="pages-profile-settings" class="btn btn-success"><i
                                class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                    </div> --}}
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content pt-4 text-muted">
                        <div class="tab-pane active" id="overview-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-xxl-3">

                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Info</h5>
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Name :</th>
                                                            <td class="text-muted">{{ $data['nama'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Mobile :</th>
                                                            <td class="text-muted">{{ $data['no_telp'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Address :</th>
                                                            <td class="text-muted"> {{ $data['alamat'] }}</td>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Admitted Date :</th>
                                                            <td class="text-muted">{{ $data['tgl_masuk'] }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                    <br>

                                    <div class="">
                                        <div class="layout-rightside">
                                            <div class="card h-100 rounded-0">
                                                <div class="card-body p-0">
                                                    <div class="p-3">
                                                        <h6 class="text-muted mb-0 text-uppercase fw-bold fs-13">Patient
                                                            Timeline
                                                        </h6>
                                                    </div>
                                                    <div data-simplebar style="max-height: 410px;" class="p-3 pt-0">
                                                        <div class="acitivity-timeline acitivity-main">

                                                            {{-- Past patient timeline: iterate `$dataJadwal` provided by controller --}}
                                                            @foreach ($dataJadwal as $jadwalPast)
                                                                <div class="acitivity-item d-flex">
                                                                    <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                                                                        <div
                                                                            class="avatar-title bg-success-subtle text-success rounded-circle">
                                                                            <i class="ri-heart-add-line"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <small
                                                                            class="mb-0 text-muted">{{ $jadwalPast['tanggal'] }}
                                                                            -
                                                                            {{ \Carbon\Carbon::parse($jadwalPast['jam'])->format('H:i') }}</small>
                                                                        <h6 class="mb-1 lh-base">
                                                                            {{ $jadwalPast['event'] }}
                                                                        </h6>
                                                                        <p class="text-muted mb-1">
                                                                            {{ $jadwalPast['catatan'] }}</p>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>

                                                </div>
                                            </div> <!-- end card-->
                                        </div> <!-- end .rightbar-->

                                    </div> <!-- end col -->

                                </div>
                                <!--end col-->
                                <div class="col-xxl-9">
                                    <div class="card crm-widget">
                                        <div class="card-body p-0">
                                            <div class="row row-cols-md-3 row-cols-1">
                                                <div class="col col-lg border-end">
                                                    <div class="py-4 px-3">
                                                        <h5 class="text-muted text-uppercase fs-14">Hear Rate
                                                        </h5>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-pulse-line display-6 text-muted"></i>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                {{-- Vitals: demo values currently provided via `rand()` — replace with live data. --}}
                                                                <h2 class="mb-0"><span class="counter-value"
                                                                        data-target="{{ rand(60, 100) }}">0</span>bpm</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col col-lg border-end">
                                                    <div class="mt-3 mt-md-0 py-4 px-3">
                                                        <h5 class="text-muted text-uppercase fs-14">Glucose
                                                        </h5>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-contrast-drop-line display-6 text-muted"></i>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h2 class="mb-0"><span class="counter-value"
                                                                        data-target="{{ rand(80, 120) }}">0</span>mg/dL
                                                                </h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col col-lg border-end">
                                                    <div class="mt-3 mt-md-0 py-4 px-3">
                                                        <h5 class="text-muted text-uppercase fs-14">
                                                            Blood Pressure
                                                        </h5>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-water-flash-line display-6 text-muted"></i>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h2 class="mb-0"><span class="counter-value"
                                                                        data-target="132">0</span>/<span
                                                                        class="counter-value"
                                                                        data-target="{{ rand(80, 100) }}">0</span>mmHg
                                                                </h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col col-lg border-end">
                                                    <div class="mt-3 mt-lg-0 py-4 px-3">
                                                        <h5 class="text-muted text-uppercase fs-14">
                                                            body temperature
                                                        </h5>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-temp-hot-line display-6 text-muted"></i>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h2 class="mb-0"><span class="counter-value"
                                                                        data-target="{{ rand(36, 38) }}">0</span>°C
                                                                </h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col col-lg">
                                                    <div class="mt-3 mt-lg-0 py-4 px-3">
                                                        <h5 class="text-muted text-uppercase fs-14">
                                                            Body Weight
                                                        </h5>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="ri-scales-2-line display-6 text-muted"></i>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h2 class="mb-0"><span class="counter-value"
                                                                        data-target="{{ rand(50, 100) }}">0</span>kg
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div><!-- end row -->
                                        </div><!-- end card body -->
                                    </div><!-- end card -->

                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Parient Description</h5>
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            {{-- Patient written description from backend --}}
                                                            <th class="ps-0" scope="row">{{ $data['deskripsi'] }}
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->

                                    <div class="row">


                                        <div class="col-xxl-6">
                                            <div class="card">
                                                <div class="card-header align-items-center d-flex">
                                                    <h4 class="card-title mb-0 flex-grow-1">Goals and Activity</h4>
                                                </div><!-- end card header -->

                                                <div class="card-body">
                                                    <div class="live-preview">
                                                        <div class="card bg-light overflow-hidden shadow-none">
                                                            <div class="card-body">
                                                                <div class="d-flex">
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0"><b
                                                                                class="text-secondary">65%</b>
                                                                            Test blood sugar every day</h6>
                                                                    </div>
                                                                    <div class="flex-shrink-0">
                                                                        <h6 class="mb-0">
                                                                            <a href="#" class="text-primary">View
                                                                                Details</a>
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="progress bg-secondary-subtle rounded-0">
                                                                <div class="progress-bar bg-secondary" role="progressbar"
                                                                    style="width: 65%" aria-valuenow="65"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>

                                                        <div class="card bg-light overflow-hidden shadow-none">
                                                            <div class="card-body">
                                                                <div class="d-flex">
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0"><b class="text-success">40%</b>
                                                                            Take medication as directed</h6>
                                                                    </div>
                                                                    <div class="flex-shrink-0">
                                                                        <h6 class="mb-0"><a href="#"
                                                                                class="text-primary">View
                                                                                Details</a></h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="progress bg-success-subtle rounded-0">
                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                    style="width: 40%" aria-valuenow="40"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>

                                                        <div class="card bg-light overflow-hidden shadow-none">
                                                            <div class="card-body">
                                                                <div class="d-flex">
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0"><b class="text-danger">10%</b>
                                                                            Maintain normal BP level</h6>
                                                                    </div>
                                                                    <div class="flex-shrink-0">
                                                                        <h6 class="mb-0"><a href="#"
                                                                                class="text-primary">View
                                                                                Details</a></h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="progress bg-danger-subtle rounded-0">
                                                                <div class="progress-bar bg-danger" role="progressbar"
                                                                    style="width: 10%" aria-valuenow="10"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div><!-- end card-body -->
                                            </div><!-- end card -->
                                        </div><!-- end col -->

                                        <div class="col-xl-6">
                                            <div class="card card-height-100">
                                                <div class="card-header align-items-center d-flex">
                                                    <h4 class="card-title mb-0 flex-grow-1 fw-bold">Action</h4>
                                                </div><!-- end card header -->

                                                <div class="card-body p-0">

                                                    <div class="align-items-center p-3 justify-content-between d-flex">
                                                        <div class="flex-shrink-0">
                                                            <div class="text-muted"><span class="fw-semibold">3</span> of
                                                                <span class="fw-semibold">3</span> remaining
                                                            </div>
                                                        </div>
                                                    </div><!-- end card header -->

                                                    <div data-simplebar style="max-height: 320px;">
                                                        <ul class="list-group list-group-flush border-dashed px-3">
                                                            <li class="list-group-item ps-0">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="form-check ps-0 flex-sharink-0">
                                                                        <input type="checkbox"
                                                                            class="form-check-input ms-0" id="task_one">
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <label class="form-check-label mb-0 ps-2"
                                                                            for="task_one">Brief medical hiistory,
                                                                            allergies
                                                                            and medication the patient</label>
                                                                    </div>
                                                                    <div class="flex-shrink-0 ms-2">
                                                                        <p class="text-muted fs-12 mb-0">07:00 AM</p>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="list-group-item ps-0">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="form-check ps-0 flex-sharink-0">
                                                                        <input type="checkbox"
                                                                            class="form-check-input ms-0" id="task_two">
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <label class="form-check-label mb-0 ps-2"
                                                                            for="task_two">surgical history to include all
                                                                            invasive Procedures the patient has
                                                                            undergone</label>
                                                                    </div>
                                                                    <div class="flex-shrink-0 ms-2">
                                                                        <p class="text-muted fs-12 mb-0">08:00 AM</p>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="list-group-item ps-0">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="form-check flex-sharink-0 ps-0">
                                                                        <input type="checkbox"
                                                                            class="form-check-input ms-0" id="task_three">
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <label class="form-check-label mb-0 ps-2"
                                                                            for="task_three">Prepare medical history
                                                                            document</label>
                                                                    </div>
                                                                    <div class="flex-shrink-0 ms-2">
                                                                        <p class="text-muted fs-12 mb-0">09:00 AM</p>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul><!-- end ul -->
                                                    </div>
                                                </div><!-- end card body -->
                                            </div><!-- end card -->
                                        </div><!-- end col -->

                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                    </div>
                    <!--end tab-content-->
                </div>

            </div>
            <!--end col-->
        </div>
        <div class="row">
            <div class="col-xl-4">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Medical History</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                                <tbody>
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">Procedures and Surgeries</h6>
                                            <span class="text-muted">Coronary Artery Bypass Grafting</span>
                                        </td>

                                        <td>
                                            <a href="#!" class="btn btn-link btn-sm">View More <i
                                                    class="ri-arrow-right-line align-bottom"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">Alergies</h6>
                                            <span class="text-muted">Penicillin and atopics eczema</span>
                                        </td>

                                        <td>
                                            <a href="#!" class="btn btn-link btn-sm">View More <i
                                                    class="ri-arrow-right-line align-bottom"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">Medication</h6>
                                            <span class="text-muted">Aspirin, Clopidogrel, Simvastatin</span>
                                        </td>

                                        <td>
                                            <a href="#!" class="btn btn-link btn-sm">View More <i
                                                    class="ri-arrow-right-line align-bottom"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">Family History</h6>
                                            <span class="text-muted">Cardiovascular Disease</span>
                                        </td>

                                        <td>
                                            <a href="#!" class="btn btn-link btn-sm">View More <i
                                                    class="ri-arrow-right-line align-bottom"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- .card-->


            </div>

            <div class="col-xl-4">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Files and Document</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                                <tbody>
                                    <tr>
                                        <td>
                                            {{-- add file logo --}}
                                            <i class="ri-file-text-fill text-primary h2"></i>
                                        </td>
                                        <td>
                                            <h6 class="mb-0">Medical Record</h6>
                                            <span class="text-muted">Medical Record from 2020</span>
                                        </td>

                                        <td>
                                            <a href="#!" class="btn btn-link btn-sm">View Doc <i
                                                    class="ri-arrow-right-line align-bottom"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{-- add file logo --}}
                                            <i class="ri-file-text-fill text-primary h2"></i>
                                        </td>
                                        <td>
                                            <h6 class="mb-0">Consent Form</h6>
                                            <span class="text-muted">Consent Form for Surgery</span>
                                        </td>
                                        <td>
                                            <a href="#!" class="btn btn-link btn-sm">View Doc <i
                                                    class="ri-arrow-right-line align-bottom"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{-- add file logo --}}
                                            <i class="ri-file-text-fill text-primary h2"></i>
                                        </td>
                                        <td>
                                            <h6 class="mb-0">Ensurance Card</h6>
                                            <span class="text-muted">Ensurance Card for 2020</span>
                                        </td>
                                        <td>
                                            <a href="#!" class="btn btn-link btn-sm">View Doc <i
                                                    class="ri-arrow-right-line align-bottom"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- .card-->


            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Upcoming Activities</h4>
                    </div><!-- end card header -->
                    <div class="card-body pt-0">
                        <ul class="list-group list-group-flush border-dashed">

                            {{-- Upcoming activities: using `collect($jadwal)->take(7)` to show up to 7 items.
                                If `$jadwal` is already a collection, the `collect()` wrapper is redundant. --}}
                            @foreach (collect($jadwal)->take(7) as $jd)
                                <li class="list-group-item ps-0">
                                    <div class="row align-items-center g-3">
                                        <div class="col-auto">
                                            <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3">
                                                <div class="text-center">
                                                    {{-- tanggal = 2024-12-08 make it 08 only --}}
                                                    <h5 class="mb-0">{{ date('d', strtotime($jd['tanggal'])) }}</h5>
                                                    {{-- tanggal = 2024-12-08 make it 3 letter of day name --}}
                                                    {{-- Day abbreviation, e.g. Mon, Tue — uses PHP `date()` on the string value --}}
                                                    <div class="text-muted">{{ date('D', strtotime($jd['tanggal'])) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h3 class="mt-0 mb-1 fs-14">{{ $jd['dokter']['nama'] }}</h3>
                                            <h3 class="mt-0 mb-1 fs-14">{{ $jd['event'] }}</h3>
                                            <h5 class="text-muted mt-0 mb-1 fs-14">
                                                {{ \Carbon\Carbon::parse($jd['jam'])->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($jd['jam'])->addMinutes(rand(30, 60))->format('H:i') }}
                                            </h5>
                                            {{-- <a href="#" class="text-reset fs-15 mb-0">Meeting for campaign with sales
                                        team</a> --}}
                                        </div>
                                    </div>
                                    <!-- end row -->
                                </li><!-- end -->
                            @endforeach

                        </ul><!-- end -->

                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div>
        <!--end row-->
    @endsection
    @section('script')
        <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>

        <script src="{{ URL::asset('build/js/pages/profile.init.js') }}"></script>
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
