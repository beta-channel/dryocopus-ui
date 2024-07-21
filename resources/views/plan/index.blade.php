@extends('layout', ['nav' => 'plan'])

@section('title', 'プラン')

@push('css')
    <link href="{{ asset('lib/choices/choices.min.css') }}" rel="stylesheet" />
    <style>
        .plan-tasks:hover {
            color: var(--falcon-primary) !important;
        }
        .plan-copy:hover {
            color: var(--falcon-warning) !important;
        }
        .plan-delete:hover {
            color: var(--falcon-danger) !important;
        }
    </style>
@endpush

@push('javascript.lib')
    <script src="{{ asset('lib/choices/choices.min.js') }}"></script>
    <script src="{{ asset('lib/jquery-mask/jquery.mask.min.js') }}"></script>
@endpush

@push('javascript')
    <script src="{{ asset('js/plan/index.js') }}"></script>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="sticky-top">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-primary mb-0">検索</h5>
                    </div>
                    <div class="card-body">
                        <form method="get" action="{{ route('plans') }}">
                            <input type="text" class="form-control form-control-sm mb-3" name="keyword" value="{{ request()->query('keyword') }}" placeholder="プラン名">
                            <button type="submit" class="btn btn-primary btn-sm w-100">検索</button>
                        </form>
                    </div>
                    <div class="card-footer border-top">
                        <button class="btn btn-sm btn-success w-100" type="button" data-bs-toggle="modal" data-bs-target="#plan-modal">プラン作成</button>
                    </div>
                </div>

                <div class="text-center mt-3 pb-5">
                    <p class="fs--1 fw-bold">総計：{{ number_format($plans->total()) }} 件</p>
                    <x-paginator :paginator="$plans" :item-count="3" :show-total="false" />
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            @forelse($plans as $plan)
                <div class="card plan-item mb-3">
                    <div class="card-header d-flex flex-between-center">
                        <h6 class="mb-0">{{ $plan->name }}</h6>
                        <div class="d-flex flex-end-center">
                            @if($plan->tasks->count() > 0)
                                <a class="plan-tasks d-flex flex-center text-secondary fw-bold me-3" href="{{ route('tasks', ['plan' => $plan->id]) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="関連タスク：{{ number_format($plan->tasks->count()) }}件"><i class="fas fa-list-check"></i>({{ number_format($plan->tasks->count()) }})</a>
                            @endif
                            {{--<a class="plan-copy me-3 text-secondary" href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="コピーして作成" data-content="{{ json_encode($plan->content) }}"><i class="fas fa-copy"></i></a>--}}
                            @if($plan->tasks->every(fn($task) => !$task->active))
                                <a class="plan-delete text-secondary" href="#modal-delete" data-bs-toggle="modal" data-id="{{ $plan->id }}" title="削除"><i class="fas fa-trash-can"></i></a>
                            @else
                                <span class="text-secondary opacity-50" data-bs-toggle="tooltip" data-bs-placement="bottom" title="全ての関連タスクを停止してから削除可能"><i class="fas fa-trash-can"></i></span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @if(\App\Models\Plan::isValidPlan($plan->content))
                                @foreach($plan->content as $schedule)
                                    <li class="list-group-item d-flex flex-between-center hover-primary">
                                        <span>{{ $schedule['time'][0] }} ~ {{ $schedule['time'][1] }}</span>
                                        <span>間隔 {{ $schedule['interval'][0] }} ~ {{ $schedule['interval'][1] }} 秒/回</span>
                                    </li>
                                @endforeach
                            @else
                                <li class="list-group-item text-danger"><i class="fas fa-circle-xmark me-2"></i>無効なプラン</li>
                            @endif
                        </ul>
                    </div>
                </div>
            @empty
                <p class="text-center mt-4">該当条件のプランがありません</p>
            @endforelse
        </div>
    </div>

    @include('common.modal-plan')

    <x-modal id="modal-delete">
        <form method="post" action="{{ route('plan.delete') }}">
            @csrf
            <div class="modal-body p-0">
                <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                    <h5 class="mb-0">削除確認</h5>
                </div>
                <p class="mb-0 py-3 px-4">
                    該当プランを削除してよろしいですか？<br />
                    <span class="text-danger fs--1">※関連タスクのプラン設定が全て解除されます！</span>
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-outline-secondary px-4" type="button" data-bs-dismiss="modal">キャンセル</button>
                <button class="btn btn-sm btn-danger px-5" type="submit">削除</button>
            </div>
        </form>
    </x-modal>
@endsection
