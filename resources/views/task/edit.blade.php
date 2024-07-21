@extends('layout', ['nav' => 'task'])

@section('title', $task === null ? 'タスク追加' : ($task->name ?? 'タスク更新'))

@push('css')
    <link href="{{ asset('lib/choices/choices.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('lib/daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
@endpush

@push('javascript.lib')
    <script src="{{ asset('lib/choices/choices.min.js') }}"></script>
    <script src="{{ asset('lib/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('lib/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('lib/jquery-mask/jquery.mask.min.js') }}"></script>
@endpush

@push('javascript')
    <script src="{{ asset('js/task/edit.js') }}"></script>
@endpush

@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">{{ $task === null ? 'タスク追加' : ($task->name ?? 'タスク更新') }}</h5>
        </div>
        <form method="post" action="{{ $task === null ? route('task.create') : route('task.update', ['task_id' => $task->id]) }}">
            @csrf
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <x-form-input label="名称" name="name" :default-value="$task?->name" placeholder="名称を入力してください" />
                    </div>
                    <div class="col-12">
                        <x-form-input label="リンク" name="link" :default-value="$task?->link" :required="true" placeholder="https://dryocopus.jp" />
                    </div>
                    <div class="col-12">
                        <div class="form-group fill">
                            <label class="form-label text-primary f-w-600 mb-2">実行プラン</label>
                            <div class="row">
                                <div class="col-6">
                                    <select class="form-select{{ $errors->has('plan_id') ? ' is-invalid' : null }}" name="plan_id">
                                        <option value="">プランを選択</option>
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}" @selected(old('plan_id', $task?->plan_id) == $plan->id)>{{ $plan->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col p-0">
                                    <button id="create-plan" class="btn btn-light" type="button" data-bs-toggle="tooltip" data-bs-placement="right" title="新しいプランを作成"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            @error('plan_id')
                                <label class="invalid-feedback">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group fill">
                            <label class="form-label text-primary f-w-600 mb-2">実行日程</label>
                            <div class="input-group">
                                <input class="form-control datepicker{{ $errors->has('start_time') ? ' is-invalid': null }}" type="text" name="start_time" value="{{ old('start_time', format_date($task?->start_time)) }}" placeholder="{{ format_date(today()) }}" />
                                <span class="input-group-text">~</span>
                                <input class="form-control datepicker{{ $errors->has('end_time') ? ' is-invalid': null }}" type="text" name="end_time" value="{{ old('end_time', format_date($task?->end_time)) }}" placeholder="{{ format_date(tomorrow()) }}" />
                            </div>
                            @error('start_time')
                                <label class="invalid-feedback">{{ $message }}</label>
                            @enderror
                            @error('end_time')
                                <label class="invalid-feedback">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border d-flex flex-between-center">
                <a class="btn btn-outline-secondary btn-sm px-5" href="{{ route('tasks') }}">戻る</a>
                <button class="btn btn-primary btn-sm px-5" type="submit">確定</button>
            </div>
        </form>
    </div>

    @include('common.modal-plan', ['mode' => 'ajax'])
@endsection
