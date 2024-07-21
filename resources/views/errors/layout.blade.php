<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">

    <title>{{ $exception->getStatusCode() }} | {{ config('app.name') }}</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("favicon-32x32.png") }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("favicon-16x16.png") }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset("favicon.ico") }}">

    <script src="{{ asset('lib/overlayscrollbars/OverlayScrollbars.min.js') }}"></script>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('lib/overlayscrollbars/OverlayScrollbars.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme.min.css') }}" rel="stylesheet" id="style-default">
    <link href="{{ asset('css/user.min.css') }}" rel="stylesheet" id="user-style-default">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <style>
        .error-content {
            font-weight: 800;
            color: #555555;
        }
    </style>
</head>
<body>
<main class="main" id="top">
    <div class="container" data-layout="container">
        <div class="row justify-content-center mt-6">
            <div class="col-10 text-center">
                <div class="d-flex flex-center mb-6">
                    <span class="text-primary font-sans-serif fw-bolder fs-6">{{ config('app.name') }}</span>
                </div>

                <div class="error-content text-center mb-5">
                    @yield('content')
                </div>
                @sectionMissing('action')
                    <a class="btn btn-primary px-5" href="/">ダッシュボードへ戻る</a>
                @endif
                @hasSection('action')
                    @yield('action')
                @endif
            </div>
        </div>

        <footer class="footer position-fixed start-0">
            <div class="row g-0 justify-content-center fs--1 mt-4 mb-3">
                <div class="col-12 col-sm-auto text-center">
                    <p class="mb-0 text-600">Copyright &copy; {{ today()->year }} Wang Runbo. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</main>

<script src="{{ asset('lib/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('lib/fontawesome/all.min.js') }}"></script>
</body>
</html>
