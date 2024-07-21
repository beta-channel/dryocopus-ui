<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name') }}</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap"
          rel="stylesheet">
    <link href="{{ asset('css/theme.min.css') }}" rel="stylesheet" id="style-default">
    <link href="{{ asset('css/user.min.css') }}" rel="stylesheet" id="user-style-default">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @stack('css')
</head>
<body>
<main class="main" id="top">
    <div class="container" data-layout="container">
        <nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
            <div class="d-flex align-items-center">
                <div class="toggle-icon-wrapper">
                    <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
                            data-bs-placement="left" title="Toggle Navigation">
                        <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
                    </button>
                </div>
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <div class="d-flex align-items-center py-3">
                        {{--<img class="me-2" src="{{ asset('img/logo.png') }}" alt="" width="40"/>--}}
                        <span class="font-sans-serif">{{ config('app.name') }}</span>
                    </div>
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
                <div class="navbar-vertical-content scrollbar">
                    <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                        {{--
                        <li class="nav-item">
                            <a class="nav-link{{ $nav === 'dashboard' ? ' active' : null }}" href="{{ route('dashboard') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon">
                                        <span class="fas fa-chart-pie"></span>
                                    </span>
                                    <span class="nav-link-text ps-1">ダッシュボード</span>
                                </div>
                            </a>
                        </li>
                        --}}
                        <li class="nav-item">
                            <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                                <div class="col-auto navbar-vertical-label">タスク管理</div>
                                <div class="col ps-0">
                                    <hr class="mb-0 navbar-vertical-divider"/>
                                </div>
                            </div>
                            <a class="nav-link{{ $nav === 'task' ? ' active' : null }}" href="{{ route('tasks') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon">
                                        <span class="fas fa-list-check"></span>
                                    </span>
                                    <span class="nav-link-text ps-1">タスク</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link{{ $nav === 'plan' ? ' active' : null }}" href="{{ route('plans') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon">
                                        <span class="fas fa-clock-rotate-left"></span>
                                    </span>
                                    <span class="nav-link-text ps-1">プラン</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link{{ $nav === 'execution' ? ' active' : null }}" href="{{ route('executions') }}" role="button" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon">
                                        <span class="fas fa-receipt"></span>
                                    </span>
                                    <span class="nav-link-text ps-1">実行履歴</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content">
            <nav class="navbar navbar-light navbar-glass navbar-top navbar-expand mb-3">
                <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button"
                        data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse"
                        aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation">
                    <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
                </button>
                <a class="navbar-brand me-0" href="{{ route('dashboard') }}">
                    <div class="d-flex align-items-center">
                        {{--<img class="me-2" src="{{ asset('img/logo.png') }}" alt="" width="40"/>--}}
                        <span class="font-sans-serif">{{ config('app.name') }}</span>
                    </div>
                </a>

                <ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link" id="navbarDropdownUser" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-flex flex-center">{{ $username }}<i class="fas fa-caret-down fs-1 ms-1" style="margin-bottom: 0.125rem"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="navbarDropdownUser">
                            <div class="bg-white dark__bg-1000 rounded-2 py-2">
                                {{--<a class="dropdown-item" href="#">利用者情報</a>--}}
                                <a class="dropdown-item" href="{{ route('security') }}">パスワード変更</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}">ログアウト</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>

            @yield('content')

            <footer class="footer">
                <div class="row g-0 justify-content-between fs--1 mt-4 mb-3">
                    <div class="col-12 col-sm-auto text-center">
                        <p class="mb-0 text-600">Copyright &copy; {{ today()->year }} Wang Runbo. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</main>

<div id="auth-modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="auth-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content none-border">
            <div class="modal-header pb-0 border-bottom-0">
                <h5 class="modal-title text-danger" id="auth-modal-label">認証情報が期限切れました</h5>
            </div>
            <div class="modal-body">
                <p class="mb-0">認証情報が期限切れたので、ごログイン直してください！</p>
            </div>
            <div class="modal-footer p-2">
                <a class="btn btn-sm btn-outline-primary float-right w-auto" href="{{ route('login') }}">ログイン画面へ</a>
            </div>
        </div>
    </div>
</div>

<div id="csrf-modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="csrf-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content none-border">
            <div class="modal-header pb-0 border-bottom-0">
                <h5 class="modal-title text-danger" id="csrf-modal-label">エラー</h5>
            </div>
            <div class="modal-body">
                <p class="mb-0">一時的に画面を操作しないため、リロードしてからもう一度試してください！</p>
            </div>
            <div class="modal-footer p-2">
                <a class="btn btn-sm btn-outline-primary float-right w-auto" href="{{ url()->current() }}">画面リロード</a>
            </div>
        </div>
    </div>
</div>

<div id="notify-box" class="position-fixed top-0 end-0 p-3" style="display: none; z-index: 9999;"></div>

<script src="{{ asset('lib/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('lib/popper/popper.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('lib/anchorjs/anchor.min.js') }}"></script>
<script src="{{ asset('/lib/is/is.min.js') }}"></script>
<script src="{{ asset('lib/fontawesome/all.min.js') }}"></script>
<script src="{{ asset('lib/lodash/lodash.min.js') }}"></script>
<script src="{{ asset('lib/list.js/list.min.js') }}"></script>
<script src="{{ asset('js/theme.min.js') }}"></script>
@stack('javascript.lib')
<script src="{{ asset('js/script.js') }}"></script>
@stack('javascript')
@if(session()->has('notify'))
    <script>
        @foreach(session()->get('notify') as $type => $messages)
        @json((array)$messages).forEach(function (message) {
            notify(message, '{{ $type }}');
        });
        @endforeach
    </script>
@endif
</body>
</html>
