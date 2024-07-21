@extends('errors.layout')

@section('content')
    <p class="fs-3">419 <span>Page Expired</span></p>
    <p class="fs-1">画面がタイムアウトしました。<br />操作を最初からやり直してください。<br />解決されない場合は、システム管理者までご連絡してください。</p>
@endsection

@section('action')
    <a class="btn btn-outline-secondary px-5" href="{{ url()->previous() }}"><i class="fas fa-reply me-2"></i>戻る</a>
@endsection
