<!DOCTYPE html>
<!--
Template Name: NobleUI - Laravel Admin Dashboard Template
Author: NobleUI
Website: https://www.nobleui.com
Contact: nobleui.team@gmail.com
License: You must have a valid license to legally use the template for your project.
-->
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
    <title>GeoGap Admin Panel</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.1/classic/ckedit.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js CDN -->
    <link href="https://cdn.jsdelivr.net/npm/lightbox2@2/dist/css/lightbox.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/vendors/flatpickr/flatpickr.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/flatpickr/flatpickr.min.css')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('assets/vendors/core/core.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/flatpickr/flatpickr.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/fonts/feather-font/css/iconfont.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/demo3/style.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <link rel="shortcut icon" href="https://geogapp.site/assets/Logo-DSp0wGmi.svg" />
    <link rel="stylesheet" href="{{asset('assets/vendors/mdi/css/materialdesignicons.min.css')}}">

</head>
<body data-base-url="https://nobleui.com/laravel/template/demo3">

<script src="https://nobleui.com/laravel/template/demo3/assets/js/spinner.js"></script>

<div class="main-wrapper border-none" id="app" style="background: #d0e3d0">
    <div class="page-wrapper full-page">
        <div class="page-content d-flex align-items-center justify-content-center">

            <div class="row w-100 mx-0 auth-page">
                <div class="col-md-8 col-xl-6 mx-auto">
                    <div class="card">
                        <div class="row">
                            <div class="col-md-4 pe-md-0"  >
                                <div class="auth-side-wrapper" style="background-image: url(https://i.pinimg.com/736x/86/45/e1/8645e14e444d69fd80149c8f6eb547b0.jpg);border-radius: 3px;">

                                </div>
                            </div>
                            <div class="col-md-8 ps-md-0">
                                <div class="auth-form-wrapper px-4 py-5" style="background: #E7F5E9 !important;">
                                    <a href="#" class="noble-ui-logo d-block mb-2">GeoGap<span class="ms-2">სამართავი პანელი</span></a>
                                    <h5 class="text-muted fw-normal mb-4">სამართავ პანელში შესვლა</h5>
                                    <form class="forms-sample" action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="userEmail" class="form-label">ელ.ფოსტა</label>
                                            <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="userEmail" placeholder="ელ.ფოსტ" value="{{ old('email') }}">
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="userPassword" class="form-label">პაროლი</label>
                                            <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="userPassword" autocomplete="current-password" placeholder="პაროლი">
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">დამიმახსოვრე</label>
                                        </div>

                                        <div  class="mt-2">
                                            <button type="submit" style="width: 100% !important; background: #348E38 !important;" class="btn text-white me-2 mb-2 mb-md-0">შესვლა</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- base js -->
<script src="https://nobleui.com/laravel/template/demo3/js/app.js"></script>
<script src="https://nobleui.com/laravel/template/demo3/assets/plugins/feather-icons/feather.min.js"></script>
<!-- end base js -->

<!-- plugin js -->
<!-- end plugin js -->

<!-- common js -->
<!-- end common js -->

</body>
</html>
