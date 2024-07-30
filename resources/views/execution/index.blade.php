@extends('layout', ['nav' => 'execution'])

@section('title', '実行履歴')

@push('javascript')
    <script src="{{ asset('js/execution/index.js') }}"></script>
@endpush

@section('content')
    <x-search-card :action="route('executions')">
        <div class="row">
            <div class="col-md-8 mb-3">
                <label class="form-label">リンク</label>
                <input type="text" class="form-control form-control-sm" name="link" value="{{ request()->query('link') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">状態</label>
                <select class="form-select form-select-sm" name="finished">
                    <option value=""></option>
                    <option value="0" @selected(request()->query('finished') === '0')>実行中</option>
                    <option value="1" @selected(request()->query('finished') === '1')>実行終了</option>
                </select>
            </div>
        </div>
    </x-search-card>

    <div class="card mt-3">
        <div class="card-body">
            <x-table>
                <thead>
                <tr>
                    <th scope="col" class="text-center text-nowrap">実行開始</th>
                    <th scope="col" class="text-center text-nowrap">実行終了</th>
                    <th scope="col" class="text-nowrap">リンク</th>
                    <th scope="col" class="text-center text-nowrap">実行プラン</th>
                    <th scope="col" class="text-center text-nowrap">終了原因</th>
                    <th scope="col" class="text-center text-nowrap">運用料</th>
                </tr>
                </thead>
                <tbody>
                @foreach($executions as $execution)
                    <tr>
                        <td class="text-center text-nowrap">
                            <span class="d-block text-start">@datetime(format_date($execution->start_time, config('app.format.datetime')))</span>
                        </td>
                        <td class="text-center text-nowrap">
                            @if($execution->finish_time === null)
                                <span class="badge bg-danger" style="width: 5rem"><i class="fas fa-forward me-2"></i>実行中</span>
                            @else
                                <span class="d-block text-start">@datetime(format_date($execution->finish_time, config('app.format.datetime')))</span>
                            @endif
                        </td>
                        <td class="text-wrap">
                            <a href="{{ $execution->link }}" target="_blank">{{ $execution->link }}</a>
                            <p class="m-0 fs--2 text-muted">{{ $execution->task_name }}</p>
                        </td>
                        <td class="text-center text-nowrap">
                            <a href="#modal-plan" class="btn btn-sm btn-primary fs--2 fw-bold px-3" data-bs-toggle="modal" data-plan="{{ json_encode($execution->plan) }}">プラン確認</a>
                        </td>
                        <td class="text-center">
                            @if($execution->finished_as === EXECUTION_FINISHED_AS_ERROR)
                                <span class="text-danger fw-bold fs--1">{{ __('messages.execution_finished_as.'.EXECUTION_FINISHED_AS_ERROR) }}</span>
                            @elseif($execution->finished_as === EXECUTION_FINISHED_AS_NORMAL)
                                <span class="fw-bold fs--1">{{ __('messages.execution_finished_as.'.EXECUTION_FINISHED_AS_NORMAL) }}</span>
                            @elseif($execution->finished_as === EXECUTION_FINISHED_AS_SCHEDULE)
                                <span class="text-warning fw-bold fs--1">{{ __('messages.execution_finished_as.'.EXECUTION_FINISHED_AS_SCHEDULE) }}</span>
                            @else
                                <span>-</span>
                            @endif
                            <p class="text-nowrap fs--2 mt-1 mb-0"><span class="text-success">{{ number_format($execution->succeed) }} 成功</span> / <span class="text-danger">{{ number_format($execution->failed) }} 失敗</span></p>
                        </td>
                        <td class="text-center">{{ $execution->cost !== null ? '¥'.number_format($execution->cost, 2) : '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </x-table>

            <x-paginator :paginator="$executions" />
        </div>
    </div>

    <x-modal id="modal-plan">
        <div class="modal-body p-0">
            <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                <h5 class="mb-0">実行プラン</h5>
            </div>
            <ul class="list-group list-group-flush"></ul>
        </div>
    </x-modal>
@endsection
