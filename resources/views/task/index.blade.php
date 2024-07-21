@extends('layout', ['nav' => 'task'])

@section('title', 'タスク')

@push('css')
    <link href="{{ asset('lib/choices/choices.min.css') }}" rel="stylesheet" />
@endpush

@push('javascript.lib')
    <script src="{{ asset('lib/choices/choices.min.js') }}"></script>
@endpush

@push('javascript')
    <script>
        var task_startup_url = '{{ route('task.startup') }}';
        var task_active_url = '{{ route('task.active') }}';
        var task_stop_url = '{{ route('task.stop') }}';
    </script>
    <script src="{{ asset('js/task/index.js') }}"></script>
@endpush

@section('content')
    <x-search-card :action="route('tasks')">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">名称</label>
                <input type="text" class="form-control form-control-sm" name="name" value="{{ request()->query('name') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">リンク</label>
                <input type="text" class="form-control form-control-sm" name="link" value="{{ request()->query('link') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">プラン</label>
                <select class="form-select form-select-sm" name="plan">
                    <option value="">&nbsp;</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" @selected(request()->query('plan') == $plan->id)>{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">状態</label>
                <select class="form-select form-select-sm" name="status">
                    <option value=""></option>
                    <option value="{{ TASK_STATUS_RUNNING }}" @selected(request()->query('status') === (string)TASK_STATUS_RUNNING)>{{ __('messages.task_status.'.TASK_STATUS_RUNNING) }}</option>
                    <option value="{{ TASK_STATUS_STOPPED }}" @selected(request()->query('status') === (string)TASK_STATUS_STOPPED)>{{ __('messages.task_status.'.TASK_STATUS_STOPPED) }}</option>
                    <option value="{{ TASK_STATUS_PREPARING }}" @selected(request()->query('status') === (string)TASK_STATUS_PREPARING)>{{ __('messages.task_status.'.TASK_STATUS_PREPARING) }}</option>
                </select>
            </div>
        </div>
    </x-search-card>

    <div class="card mt-3">
        <div class="card-body">
            <div class="mb-4">
                <a href="{{ route('task.create') }}" class="btn btn-sm btn-success px-3">タスク追加</a>
            </div>
            <x-table>
                <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">名称</th>
                    <th scope="col">リンク</th>
                    <th scope="col">実行プラン</th>
                    <th scope="col" class="text-center">実行日程</th>
                    <th scope="col" class="text-center">状態</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <th>{{ $loop->iteration }}</th>
                        <td>{{ $task->name ?? '-' }}</td>
                        <td class="text-wrap"><a href="{{ $task->link }}" target="_blank">{{ $task->link }}</a></td>
                        <td class="task-plan" data-plan="{{ $task->plan?->id }}">
                            @if($task->plan !== null)
                                <a href="#modal-plan" class="d-flex align-items-center fs--1" data-bs-toggle="modal" data-plan="{{ json_encode($task->plan->content) }}">{{ $task->plan->name }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center task-schedule" data-preparing="{{ $task->start_time !== null && now() < $task->start_time }}">
                            @if($task->start_time !== null || $task->end_time !== null)
                                {{ format_date($task->start_time) }} ~ {{ format_date($task->end_time) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center task-status">
                            @if($task->status === TASK_STATUS_RUNNING)
                                <span class="badge rounded-pill bg-danger" style="width: 5rem">{{ __('messages.task_status.'.TASK_STATUS_RUNNING) }}</span>
                            @elseif($task->status === TASK_STATUS_PREPARING)
                                <span class="badge rounded-pill badge-soft-warning" style="width: 5rem">{{ __('messages.task_status.'.TASK_STATUS_PREPARING) }}</span>
                            @elseif($task->status === TASK_STATUS_STARTUP)
                                <span class="badge rounded-pill badge-soft-danger" style="width: 5rem"><i class="fas fa-hourglass-half me-2"></i>{{ __('messages.task_status.'.TASK_STATUS_STARTUP) }}</span>
                            @elseif($task->status === TASK_STATUS_STOPPING)
                                <span class="badge rounded-pill badge-soft-secondary" style="width: 5rem"><i class="fas fa-hourglass-half me-2"></i>{{ __('messages.task_status.'.TASK_STATUS_STOPPING) }}</span>
                            @else
                                <span class="badge rounded-pill bg-secondary" style="width: 5rem">{{ __('messages.task_status.'.TASK_STATUS_STOPPED) }}</span>
                            @endif
                        </td>
                        <td class="text-end task-operation">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-light dropdown-toggle px-3" type="button" data-bs-toggle="dropdown">操作<i class="fas fa-caret-down fs--1 ms-2"></i></button>
                                <div class="dropdown-menu dropdown-menu-end py-0">
                                    <div class="task-operation-container bg-white dark__bg-1000 rounded-2 py-2">
                                        @if($task->status === TASK_STATUS_STOPPED)
                                            @if($task->start_time !== null && now() < $task->start_time)
                                                @if($task->plan === null)
                                                    <span class="dropdown-item fw-bold disabled" data-bs-toggle="tooltip" data-bs-placement="right" title="プランを指定してください"><i class="fas fa-bolt me-2"></i>有効にする</span>
                                                @else
                                                    <a class="dropdown-item fw-bold task-exe" href="{{ route('task.active') }}" data-id="{{ $task->id }}"><i class="fas fa-bolt text-warning me-2"></i>有効にする</a>
                                                @endif
                                            @else
                                                @if($task->plan === null)
                                                    <span class="dropdown-item fw-bold disabled" data-bs-toggle="tooltip" data-bs-placement="right" title="プランを指定してください"><i class="fas fa-play me-2"></i>実行開始</span>
                                                @else
                                                    <a class="dropdown-item fw-bold task-exe" href="{{ route('task.startup') }}" data-id="{{ $task->id }}"><i class="fas fa-play text-success me-2"></i>実行開始</a>
                                                @endif
                                            @endif
                                        @elseif($task->status === TASK_STATUS_STARTUP)
                                            <span class="dropdown-item fw-bold disabled"><i class="fas fa-play me-2"></i>起動中</span>
                                        @elseif($task->status === TASK_STATUS_STOPPING)
                                            <span class="dropdown-item fw-bold disabled"><i class="fas fa-stop me-2"></i>停止中</span>
                                        @else
                                            <a class="dropdown-item fw-bold task-exe" href="{{ route('task.stop') }}" data-id="{{ $task->id }}"><i class="fas fa-stop text-danger me-2"></i>停止</a>
                                        @endif
                                        <div class="dropdown-divider"></div>
                                        @if($task->status === TASK_STATUS_STOPPED)
                                            <a class="dropdown-item task-edit" href="{{ route('task.edit', ['task_id' => $task->id]) }}">編集</a>
                                            <a class="dropdown-item text-danger task-delete" href="#modal-delete" data-bs-toggle="modal" data-id="{{ $task->id }}">削除</a>
                                        @else
                                            <span class="dropdown-item task-edit disabled" data-bs-toggle="tooltip" data-bs-placement="right" title="タスク有効中" data-href="{{ route('task.edit', ['task_id' => $task->id]) }}">編集</span>
                                            <span class="dropdown-item text-danger task-delete disabled" data-bs-toggle="tooltip" data-bs-placement="right" title="タスク有効中">削除</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </x-table>

            <x-paginator :paginator="$tasks" />
        </div>
    </div>

    <x-modal id="modal-plan">
        <div class="modal-body p-0">
            <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                <h5 class="mb-0"></h5>
            </div>
            <ul class="list-group list-group-flush"></ul>
        </div>
    </x-modal>

    <x-modal id="modal-delete">
        <form method="post" action="{{ route('task.delete') }}">
            @csrf
            <div class="modal-body p-0">
                <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                    <h5 class="mb-0">削除確認</h5>
                </div>
                <p class="mb-0 py-3 px-4">選択したタスクを削除してよろしいですか？</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-outline-secondary px-4" type="button" data-bs-dismiss="modal">キャンセル</button>
                <button class="btn btn-sm btn-danger px-5" type="submit">削除</button>
            </div>
        </form>
    </x-modal>
@endsection
