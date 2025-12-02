@php
    use App\Helpers\MyHelper;
@endphp
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
                Home dashboard view

                - Controller provides `$data`, which includes:
                    - `medical_checkup_history` : a LengthAwarePaginator of past events
                - Uses `MyHelper::formatDateTime($date, $time)` to render event date/time
                - Includes UI to request LLM-based food recommendations and to input
                    nutrition descriptions. The JS below calls routes defined in the
                    controllers (e.g. `pasien.getFoodRecommendation`, `pasien.getHeartRate`).
        --}}
    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <h2 class="mb-1 fw-bold">Good Morning, Anna!</h2>
                            </div>
                            <div>
                                {{--
                                    Action buttons:
                                    - `Pilih Menu Makanan` opens `#mealOptionsModal` where a user
                                      can select meal options from preset choices.
                                    - `Input Nutrisi` opens `#nutritionInputModal` where a user
                                      can type a free-form description; that description is
                                      sent to the server to request an LLM food recommendation.
                                --}}
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#mealOptionsModal">
                                    Pilih Menu Makanan
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#nutritionInputModal">
                                    Input Nutrisi
                                </button>
                            </div>
                        </div><!-- end card header -->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="swiper marketplace-swiper rounded gallery-light">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="card explore-box card-animate rounded">
                                        <div class="explore-place-bid-img">
                                            <img src="/images/pp.jpg" alt=""
                                                class="img-fluid card-img-top explore-img" />
                                            <div class="bg-overlay"></div>
                                        </div>
                                        <div class="card-body">
                                            <p class="fw-medium mb-0 float-end"><i class="text-danger align-middle"></i>
                                                18 Years old </p>
                                            <h5 class="mb-1"><a href="apps-nft-item-details" class="text-body">Anna binti
                                                    Anna</a></h5>
                                            <p class="text-muted mb-0">Female</p>
                                        </div>
                                        <div class="card-footer border-top border-top-dashed">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 fs-14">
                                                    <i class="text-warning align-bottom me-1"></i>
                                                    Diagnose: <span class="fw-medium">Flue</span>
                                                </div>
                                                <h5 class="flex-shrink-0 fs-14 text-primary mb-0">4 Days Opname</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="alert alert-warning border-0 rounded-0 m-0 d-flex align-items-center"
                                    role="alert">
                                    <i data-feather="alert-triangle" class="text-warning me-2 icon-sm"></i>
                                    <div id="status_pasien" class="flex-grow-1">
                                        checking your status.
                                    </div>
                                </div>

                                <div class="row align-items-end">
                                    <div class="col-sm-12">
                                        <div class="p-3">
                                            <p id="recommendation" class="fs-16 lh-base text-justify">checking your
                                                recommendation</p>
                                            <div class="mt-3">
                                                <a class="btn btn-success" onclick="consult()">Consult
                                                    Now!</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-body-->
                        </div>
                    </div> <!-- end col-->
                    <!--end col-->
                </div>

                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate bg-primary">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h2 class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                            Heart Rate</h2>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div class="d-flex justify-content-center w-100">
                                        <h4 class="fs-22 fw-bold ff-secondary text-white mb-4 text-center">
                                            <span id="heartrate" class="counter-value" data-target="124">0</span> Bpm
                                        </h4>

                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-10 rounded fs-3">
                                            <i class="bx bxs-heart text-white"></i>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate bg-secondary">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h2 class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                            Blood Pressure</h2>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div class="d-flex justify-content-center w-100">
                                        <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">
                                            <span id="bloodpressuresystolic" class="counter-value"
                                                data-target="120"></span>/
                                            <span id="bloodpressurediastolic" class="counter-value" data-target="80"></span>
                                            mmHg
                                        </h4>

                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-10 rounded fs-3">
                                            <i class="bx bx-donate-blood text-white"></i>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate bg-success">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h2 class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                            SpO2</h2>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div class="d-flex justify-content-center w-100">
                                        <h4 id="spo2" class="fs-22 fw-bold ff-secondary text-white mb-4"><span
                                                class="counter-value" data-target="98"></span>%
                                        </h4>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-10 rounded fs-3">
                                            <i class="bx bx-leaf text-white"></i>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate bg-info">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h2 class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                            Sleep Duration</h2>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div class="d-flex justify-content-center w-100">
                                        <h4 class="fs-22 fw-bold ff-secondary text-white mb-4"><span class="counter-value"
                                                data-target="7.8"></span> H
                                        </h4>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-white bg-opacity-10 rounded fs-3">
                                            <i class="bx bx-sleepy text-white"></i>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div> <!-- end row-->

                <div class="row">
                    <div class="col-xl-7">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1 fw-bold">Riwayat Pemeriksaan</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                                        <thead class="table-light">
                                            <tr class="text-muted">
                                                <th scope="col">Event</th>
                                                <th scope="col" style="width: 20%;">Pelaksanaan</th>
                                                <th scope="col">Oleh</th>
                                                <th scope="col" style="width: 16%;">Catatan</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($data['medical_checkup_history'] as $event)
                                                <tr>
                                                    {{-- Event row for medical checkup history --}}
                                                    <td>{{ $event['event'] }}</td>
                                                    {{-- Use helper to render combined date+time in human format --}}
                                                    <td>{{ MyHelper::formatDateTime($event['tanggal'], $event['jam']) }}
                                                    </td>
                                                    {{-- <td><img src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" --}}
                                                    <td><img src="{{ $event['dokter']['avatar'] }}" alt=""
                                                            class="avatar-xs rounded-circle me-2">
                                                        <a href="#javascript: void(0);"
                                                            class="text-body fw-semibold">{{ $event['dokter']['nama'] }}</a>
                                                    </td>
                                                    <td><button type="button" class="btn btn-info-subtle text-info p-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalScrollable{{ $event['id'] }}">Lihat</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody><!-- end tbody -->
                                    </table><!-- end table -->
                                </div><!-- end table responsive -->
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $data['medical_checkup_history']->onEachSide(2)->links('pagination::bootstrap-4') }}
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-5">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1 fw-bold">Daily Tasks</h4>
                            </div><!-- end card header -->

                            <div class="card-body p-0">

                                <div class="align-items-center p-3 justify-content-between d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="text-muted"><span class="fw-semibold">10</span> of <span
                                                class="fw-semibold">10</span> remaining</div>
                                    </div>
                                </div><!-- end card header -->

                                <div data-simplebar style="max-height: 320px;">
                                    <ul class="list-group list-group-flush border-dashed px-3">
                                        <li class="list-group-item ps-0">
                                            <div class="d-flex align-items-start">
                                                <div class="form-check ps-0 flex-sharink-0">
                                                    <input type="checkbox" class="form-check-input ms-0" id="task_one">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <label class="form-check-label mb-0 ps-2" for="task_one">Sarapan
                                                        pagi</label>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <p class="text-muted fs-12 mb-0">07:00 AM</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            <div class="d-flex align-items-start">
                                                <div class="form-check ps-0 flex-sharink-0">
                                                    <input type="checkbox" class="form-check-input ms-0" id="task_two">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <label class="form-check-label mb-0 ps-2" for="task_two">Minum
                                                        obat
                                                        dan vitamin</label>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <p class="text-muted fs-12 mb-0">08:00 AM</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            <div class="d-flex align-items-start">
                                                <div class="form-check flex-sharink-0 ps-0">
                                                    <input type="checkbox" class="form-check-input ms-0" id="task_three">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <label class="form-check-label mb-0 ps-2" for="task_three">Kontrol
                                                        tekanan darah</label>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <p class="text-muted fs-12 mb-0">09:00 AM</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            <div class="d-flex align-items-start">
                                                <div class="form-check ps-0 flex-sharink-0">
                                                    <input type="checkbox" class="form-check-input ms-0" id="task_four">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <label class="form-check-label mb-0 ps-2" for="task_four">Makan
                                                        siang</label>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <p class="text-muted fs-12 mb-0">12:00 PM</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            <div class="d-flex align-items-start">
                                                <div class="form-check ps-0 flex-sharink-0">
                                                    <input type="checkbox" class="form-check-input ms-0" id="task_five">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <label class="form-check-label mb-0 ps-2" for="task_five">Minum
                                                        obat
                                                        dan vitamin</label>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <p class="text-muted fs-12 mb-0">01:00 PM</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            <div class="d-flex align-items-start">
                                                <div class="form-check ps-0 flex-sharink-0">
                                                    <input type="checkbox" class="form-check-input ms-0" id="task_six">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <label class="form-check-label mb-0 ps-2" for="task_six">Istirahat
                                                        siang</label>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <p class="text-muted fs-12 mb-0">02:00 PM</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            <div class="d-flex align-items-start">
                                                <div class="form-check ps-0 flex-sharink-0">
                                                    <input type="checkbox" class="form-check-input ms-0" id="task_seven">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <label class="form-check-label mb-0 ps-2" for="task_seven">Kontrol
                                                        tekanan darah</label>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <p class="text-muted fs-12 mb-0">04:00 PM</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            <div class="d-flex align-items-start">
                                                <div class="form-check ps-0 flex-sharink-0">
                                                    <input type="checkbox" class="form-check-input ms-0" id="task_eight">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <label class="form-check-label mb-0 ps-2" for="task_eight">Makan
                                                        malam</label>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <p class="text-muted fs-12 mb-0">07:00 PM</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            <div class="d-flex align-items-start">
                                                <div class="form-check ps-0 flex-sharink-0">
                                                    <input type="checkbox" class="form-check-input ms-0" id="task_nine">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <label class="form-check-label mb-0 ps-2" for="task_nine">Minum
                                                        obat
                                                        dan vitamin</label>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <p class="text-muted fs-12 mb-0">08:00 PM</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item ps-0">
                                            <div class="d-flex align-items-start">
                                                <div class="form-check ps-0 flex-sharink-0">
                                                    <input type="checkbox" class="form-check-input ms-0" id="task_ten">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <label class="form-check-label mb-0 ps-2" for="task_ten">Tidur
                                                        malam</label>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <p class="text-muted fs-12 mb-0">10:00 PM</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul><!-- end ul -->
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div><!-- end row -->

            </div> <!-- end .h-100-->

        </div> <!-- end col -->
    </div>

    <!-- Meal Options Modal -->
    <div class="modal fade" id="mealOptionsModal" tabindex="-1" role="dialog" aria-labelledby="mealOptionsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mealOptionsModalLabel">Pilih Menu Makanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="breakfast" class="form-label">Sarapan</label>
                            <select class="form-select" id="breakfast">
                                <option selected>Pilih menu sarapan</option>
                                <option value="1">Bubur ayam tanpa santan</option>
                                <option value="2">Telur rebus</option>
                                <option value="3">Roti gandum</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="lunch" class="form-label">Makan Siang</label>
                            <select class="form-select" id="lunch">
                                <option selected>Pilih menu makan siang</option>
                                <option value="1">Ikan salmon panggang</option>
                                <option value="2">Nasi merah</option>
                                <option value="3">Brokoli kukus</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dinner" class="form-label">Makan Malam</label>
                            <select class="form-select" id="dinner">
                                <option selected>Pilih menu makan malam</option>
                                <option value="1">Sup sayuran dengan tahu kukus</option>
                                <option value="2">Ayam panggang</option>
                                <option value="3">Salad sayuran</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nutrition Input Modal -->
    <div class="modal fade" id="nutritionInputModal" tabindex="-1" role="dialog"
        aria-labelledby="nutritionInputModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">



                    <h5 class="modal-title" id="nutritionInputModalLabel">Deskripsikan makanan yang akan dikonsumsi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <label class="form-label" for="deskripsi">Deskripsi</label>
                        <div class="input-group">
                            <textarea type="text" class="form-control" id="deskripsi" rows="4"></textarea>
                        </div>

                        <!-- Container to show LLM food recommendation result -->
                        <div id="food-recommendation-result" class="mt-3" style="display:none;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="llm-recom-food">
                        <span id="llm-recom-food-spinner" class="spinner-border spinner-border-sm me-2" role="status"
                            aria-hidden="true" style="display:none;"></span>
                        <span id="llm-recom-food-text">Simpan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @foreach ($data['medical_checkup_history'] as $event)
        <div class="modal fade" id="exampleModalScrollable{{ $event['id'] }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="exampleModalScrollableTitle">Catatan Dokter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6 class="fs-16 fw-bold">Rekomendasi Nutrisi</h6>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="text-muted mb-0"><span class="fw-bold">Cataatan:</span>{{ $event['catatan'] }}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="text-muted mb-0"><span class="fw-bold">Menu Makanan:</span> <br>
                                    Sarapan: Bubur ayam tanpa santan, telur rebus.<br>
                                    Makan Siang: Ikan salmon panggang, nasi merah, brokoli kukus.<br>
                                    Makan Malam: Sup sayuran dengan tahu kukus.<br>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="text-muted mb-0"><span class="fw-bold">Pantangan:</span> Hindari makanan
                                    berlemak
                                    tinggi, gorengan, dan gula
                                    berlebih.
                                </p>
                            </div>
                        </div>
                        <h6 class="fs-16 mt-3">Obat dan Vitamin</h6>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="text-muted mb-0"><span class="fw-bold">Obat:</span> <br>
                                    Paracetamol (500 mg): 3x sehari setelah makan.<br>
                                    Antibiotik Cefadroxil (500 mg): 2x sehari selama 7 hari.</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="text-muted mb-0"><span class="fw-bold">Vitamin:</span> <br>
                                    Vitamin C: 500 mg per hari untuk mendukung pemulihan.<br>
                                    Vitamin D: 1000 IU per hari untuk meningkatkan sistem kekebalan.</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="text-muted mb-0"><span class="fw-bold">Catatan untuk Apoteker:</span> Harap
                                    siapkan
                                    obat dan vitamin sesuai resep
                                    untuk pemberian harian.
                            </div>
                        </div>
                        <h6 class="fs-16 mt-3"><span class="fw-bold">Tindakan Medis</span></h6>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="text-muted mb-0"><span class="fw-bold">Operasi:</span> <br>
                                    Jenis Tindakan: Pengangkatan cairan di bagian perut (laparoskopi).<br>
                                    Waktu: 25 Oktober 2024, pukul 09.00 pagi.<br>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="text-muted mb-0"><span class="fw-bold">Pengawasan Pasca Operasi:</span>
                                    Observasi
                                    setiap 2 jam selama 24 jam
                                    pertama.</p>
                            </div>
                        </div>
                        <h6 class="fs-16 mt-3"><span class="fw-bold">Pengambilan Sampel untuk Pemeriksaan
                                Laboratorium</span>
                        </h6>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="text-muted mb-0"><span class="fw-bold">Jenis Sampel:</span> <br>
                                    Darah: Untuk pemeriksaan hemoglobin, leukosit, dan protein C-reaktif.<br>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="text-muted mb-0"><span class="fw-bold">Tanggal Pengambilan:</span> 24 Oktober
                                    2024,
                                    pukul 07.00 pagi.</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="text-muted mb-0"><span class="fw-bold">Instruksi untuk Laboratorium:</span>
                                    Hasil
                                    diharapkan selesai dalam waktu
                                    24 jam dan dilaporkan ke dokter yang bertanggung jawab.</p>
                            </div>
                        </div>

                        <h6 class="fs-16 mt-3"><span class="fw-bold">Catatan Lainnya</span></h6>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="text-muted mb-0"><span class="fw-bold">Pengingat untuk Nurse:</span> Pantau
                                    tekanan
                                    darah pasien setiap 6 jam.
                                    Jika tekanan darah melebihi 140/90 mmHg, segera laporkan ke dokter yang bertugas.
                                </p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-fill text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <p class="text-muted mb-0"><span class="fw-bold">Kondisi Pasien:</span> Pasien perlu
                                    beristirahat cukup, disarankan
                                    pembatasan aktivitas fisik berlebih untuk 3 hari ke depan.</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    @endforeach
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <!-- dashboard init -->
    <script src="{{ URL::asset('build/js/pages/dashboard-ecommerce.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            console.log("Document ready");

            // Handle click on the LLM food recommendation button
            $('#llm-recom-food').on('click', function() {
                // Read the free-form description provided by the user
                var foodDescription = $('#nutritionInputModal textarea#deskripsi').val();
                console.log("Deskripsi makanan:", foodDescription);

                // clear previous result
                $('#food-recommendation-result').hide().html('');

                // show spinner and disable button while the request is in-flight
                $('#llm-recom-food-spinner').show();
                $('#llm-recom-food').prop('disabled', true);
                $('#llm-recom-food-text').text('Menyimpan...');

                $.ajax({
                    url: '{{ route('pasien.getFoodRecommendation') }}',
                    method: 'POST',
                    data: {
                        description: foodDescription,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Rekomendasi makanan diterima:', response);

                        // Normalization: many APIs return nested payloads. Try
                        // several common shapes and fall back to the raw response.
                        var foodData = null;
                        if (!response) {
                            foodData = null;
                        } else if (response.data && response.data.Data) {
                            foodData = response.data.Data;
                        } else if (response.data && response.data.output && response.data.output
                            .Data) {
                            foodData = response.data.output.Data;
                        } else if (response.data) {
                            foodData = response.data;
                        } else if (response.Data) {
                            foodData = response.Data;
                        } else if (response.output && response.output.Data) {
                            foodData = response.output.Data;
                        } else {
                            // fallback: try the response object itself
                            foodData = response;
                        }

                        // If we received a structured object, render keys we expect
                        if (foodData && typeof foodData === 'object') {
                            var keys = ['makan_pagi', 'selingan_pagi', 'makan_siang',
                                'selingan_sore', 'makan_malam'
                            ];
                            var html = '<div class="card"><div class="card-body">';
                            html += '<h6 class="fw-bold">Rekomendasi Menu Makanan</h6>';
                            html += '<ul class="list-unstyled mb-0">';

                            keys.forEach(function(k) {
                                if (foodData[k]) {
                                    // convert key to human label, e.g. makan_pagi -> Makan Pagi
                                    var label = k.replace(/_/g, ' ').replace(/\b\w/g,
                                        function(l) {
                                            return l.toUpperCase();
                                        });
                                    html += '<li class="mb-2"><strong>' + label +
                                        ':</strong> ' + foodData[k] + '</li>';
                                }
                            });

                            html += '</ul></div></div>';

                            $('#food-recommendation-result').html(html).show();
                        } else {
                            $('#food-recommendation-result').html(
                                '<div class="alert alert-warning">Data rekomendasi tidak ditemukan.</div>'
                                ).show();
                            console.warn('Struktur data rekomendasi tidak dikenali:', response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Gagal mendapatkan rekomendasi makanan:', error);
                        $('#food-recommendation-result').html(
                            '<div class="alert alert-danger">Gagal mengambil rekomendasi. Silakan coba lagi.</div>'
                            ).show();
                    },
                    complete: function() {
                        // hide spinner and re-enable button regardless of success/error
                        $('#llm-recom-food-spinner').hide();
                        $('#llm-recom-food').prop('disabled', false);
                        $('#llm-recom-food-text').text('Simpan');
                    }
                });
            });

            // Fetch the current heart rate from the API and update the UI.
            // Expected response: a JSON number or object that contains the value.
            function updateHeartRate() {
                $.ajax({
                    url: '{{ route('pasien.getHeartRate') }}',
                    method: 'GET',
                    dataType: 'json', // Pastikan menerima JSON
                    success: function(data) {
                        console.log("Heart Rate Data:", data); // Debugging
                        // If the API returns a plain value, use it directly. If it returns
                        // an object, adapt accordingly (e.g. { heartRate: 98 }). Update
                        // this logic if your API payload shape changes.
                        if (data !== undefined) {
                            // If data is an object with a numeric property, prefer it
                            if (typeof data === 'object' && data.heartRate !== undefined) {
                                $('#heartrate').text(data.heartRate);
                            } else if (typeof data === 'number' || typeof data === 'string') {
                                $('#heartrate').text(data);
                            } else {
                                console.warn("Unrecognized heart rate payload:", data);
                            }
                        } else {
                            console.warn("Data heart rate tidak ditemukan:", data);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Gagal mengambil heart rate:", error);
                    }
                });
            }

            // Fetch systolic/diastolic blood pressure and render to UI.
            // Expected response shape: { bloodPressure: { systolic: 120, diastolic: 80 } }
            function updateBloodPressure() {
                $.ajax({
                    url: '{{ route('pasien.getBloodPressure') }}',
                    method: 'GET',
                    dataType: 'json', // Pastikan menerima JSON
                    success: function(data) {
                        console.log("Blood Pressure Data:", data); // Debugging
                        if (data && data.bloodPressure && data.bloodPressure.systolic !== undefined &&
                            data.bloodPressure.diastolic !== undefined) {
                            $('#bloodpressuresystolic').text(data.bloodPressure.systolic);
                            $('#bloodpressurediastolic').text(data.bloodPressure.diastolic);
                        } else if (data && data.systolic !== undefined && data.diastolic !==
                            undefined) {
                            // Some APIs may return top-level properties
                            $('#bloodpressuresystolic').text(data.systolic);
                            $('#bloodpressurediastolic').text(data.diastolic);
                        } else {
                            console.warn("Data blood pressure tidak ditemukan:", data);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Gagal mengambil blood pressure:", error);
                    }
                });
            }

            // Fetch SpO2 percentage and show it. Expects either a number or
            // an object like { spo2: 98 }.
            function updateSpO2() {
                $.ajax({
                    url: '{{ route('pasien.getSpo2') }}',
                    method: 'GET',
                    dataType: 'json', // Pastikan menerima JSON
                    success: function(data) {
                        if (data && data.spo2 !== undefined) {
                            $('#spo2').text(data.spo2 + '%');
                        } else if (typeof data === 'number' || typeof data === 'string') {
                            $('#spo2').text(data + '%');
                        } else {
                            console.warn("Data SpO2 tidak ditemukan:", data);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Gagal mengambil SpO2:", error);
                    }
                });
            }

            // --- FUNGSI BARU UNTUK KONSULTASI ---
            function consult() {
                console.log("Memanggil fungsi consult..."); // Log untuk debugging

                $.ajax({
                    url: '{{ route('pasien.consult') }}', // Ensure this route exists
                    method: 'GET',
                    dataType: 'json', // Ensure the server responds with JSON
                    success: function(response) {
                        console.log('Konsultasi berhasil diproses:', response);

                        // Many API responses wrap data under different keys; this
                        // code assumes `response.data.Data` contains the useful
                        // payload. Update these checks if your API returns a
                        // different structure (see normalization examples above).
                        if (response && response.data && response.data.Data) {
                            var apiData = response.data.Data;

                            // Update patient status if present
                            if (apiData.status) {
                                $('#status_pasien').text(apiData.status);
                            } else {
                                console.warn('Property "status" not found in API response');
                            }

                            // Update recommendation text if present
                            if (apiData.recommendation) {
                                $('#recommendation').text(apiData.recommendation);
                            } else {
                                console.warn('Property "recommendation" not found in API response');
                            }
                        } else {
                            console.error('Unexpected API response structure:', response);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Show server error text in console for debugging
                        console.error('Gagal memproses konsultasi:', xhr.responseText);
                    }
                });
            }

            // Run initial fetches
            updateHeartRate();
            updateBloodPressure();
            updateSpO2();
            consult();

            function updateAll() {
                updateHeartRate();
                updateBloodPressure();
                updateSpO2();
            }
            // Poll vitals every 10 seconds
            setInterval(updateAll, 10000);

            // Periodic consult call — the value below is `60000000` (60,000,000 ms)
            // which equals ~16.6 hours. The commented note claimed 60000 = 1
            // minute; if you intend to call `consult()` every minute, change
            // this value to `60000`. Leaving unchanged to preserve existing behavior.
            setInterval(consult, 60000000);

        });
    </script>
@endsection
