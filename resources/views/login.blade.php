<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">

    <title>ログイン | {{ config("app.name") }}</title>

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
</head>
<body>
<main class="main" id="top">
    <div class="container" data-layout="container">
        <div class="row justify-content-center mt-5">
            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-6 col-xxl-6">
                <div class="d-flex flex-center mb-4">
                    <span class="text-primary font-sans-serif fw-bolder fs-5">{{ config('app.name') }}</span>
                </div>
                <div class="card">
                    <div class="card-body p-4 p-sm-5">
                        <div class="row flex-between-center mb-3">
                            <div class="col-auto">
                                <h5>ログイン</h5>
                            </div>
                        </div>
                        @if(session()->has('auth.id'))
                            <div class="alert alert-danger fs--1" role="alert">{{ __('auth.failed') }}</div>
                        @endif
                        <form id="login-form" method="post" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <input class="form-control" type="text" name="id" value="{{ session()->get('auth.id') }}" placeholder="ログインID" />
                            </div>
                            <div class="mb-3">
                                <input class="form-control" type="password" name="pass" value="" placeholder="パスワード" />
                            </div>

                            <div class="mt-4 mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit">ログイン</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="{{ asset('lib/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/bootstrap.min.js') }}"></script>
<script>
    $('#login-form').submit(function () {
        var $id = $(this).find('input[name="id"]').removeClass('is-invalid');
        var $pass = $(this).find('input[name="pass"]').removeClass('is-invalid');
        if ($id.val() === '') {
            $id.addClass('is-invalid');
        }
        if ($pass.val() === '') {
            $pass.addClass('is-invalid');
        }
        return $id.val() !== '' && $pass.val() !== '';
    });
</script>
</body>
</html>
