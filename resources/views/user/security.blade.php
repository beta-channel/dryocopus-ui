@extends('layout')

@section('title', 'パスワード変更')

@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">パスワード変更</h5>
        </div>
        <form method="post" action="{{ route('password.change') }}">
            @csrf
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-group fill">
                            <label class="form-label text-primary f-w-600 mb-2 required">元パスワード</label>
                            <input type="password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : null }}" name="current_password" value="" placeholder="元パスワードを入力してください">
                            @error('current_password')
                                <label class="invalid-feedback">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group fill">
                            <label class="form-label text-primary f-w-600 mb-2 required">新しいパスワード</label>
                            <input type="password" class="form-control{{ $errors->has('new_password') ? ' is-invalid' : null }}" name="new_password" value="" placeholder="新しいパスワードを入力してください">
                            @error('new_password')
                                <label class="invalid-feedback">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group fill">
                            <label class="form-label text-primary f-w-600 mb-2 required">新しいパスワード確認</label>
                            <input type="password" class="form-control{{ $errors->has('new_password_confirm') ? ' is-invalid' : null }}" name="new_password_confirm" value="" placeholder="新しいパスワードをもう一度入力してください">
                            @error('new_password_confirm')
                                <label class="invalid-feedback">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border d-flex flex-between-center">
                <a class="btn btn-outline-secondary btn-sm px-5" href="{{ url()->previous() }}">戻る</a>
                <button class="btn btn-primary btn-sm px-5" type="submit">確定</button>
            </div>
        </form>
    </div>
@endsection
