<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExecutionFailedRequest;
use App\Http\Requests\ExecutionFinishRequest;
use App\Http\Requests\ExecutionStartRequest;
use App\Http\Requests\ExecutionSucceedRequest;
use App\Repositories\ExecutionRepository;
use App\Repositories\TaskRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExecutionController extends Controller
{
    protected TaskRepository $task;

    protected ExecutionRepository $execution;

    public function __construct(TaskRepository $task, ExecutionRepository $execution)
    {
        $this->execution = $execution;
        $this->task = $task;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $executions = $this->execution->list([
            'task_name' => $request->query('task_name'),
            'link_name' => $request->query('link'),
            'finished' => $request->query('finished'),
        ], config('app.per_page'));

        return view('execution.index', [
            'executions' => $executions,
        ]);
    }

    /**
     * 実行開始
     * @param ExecutionStartRequest $request
     * @return JsonResponse
     */
    public function start(ExecutionStartRequest $request): JsonResponse
    {
        $task_id = $request->validated('process_id');
        $task = $this->task->find($task_id);
        if ($task === null) {
            abort(400, __('messages.task.not_exists', ['task_id' => $task_id]));
        }
        if (in_array($task->status, [TASK_STATUS_RUNNING, TASK_STATUS_STOPPING])) {
            abort(400, __('messages.task.already_running'));
        }

        $start_time = $request->validated('start_time');
        DB::transaction(function () use ($task, $start_time) {
            $this->task->set(['status' => TASK_STATUS_RUNNING], $task);
            $this->execution->createFromTask($task, $start_time);
        });

        return response()->json();
    }

    /**
     * 実行停止
     * @param ExecutionFinishRequest $request
     * @return JsonResponse
     */
    public function finish(ExecutionFinishRequest $request): JsonResponse
    {
        $data = $request->validated();
        $task_id = $data['process_id'];
        $execution = $this->execution->getRunningByTaskId($task_id);
        if ($execution === null) {
            abort(400, __('messages.execution.not_exists', ['task_id' => $task_id]));
        }

        $this->execution->update([
            'finish_time' => to_date($data['finish_time'], 'Y-m-d H:i:s', false),
            'finished_as' => match ($data['finished_as']) {
                'error' => EXECUTION_FINISHED_AS_ERROR,
                'normal' => EXECUTION_FINISHED_AS_NORMAL,
                'schedule' => EXECUTION_FINISHED_AS_SCHEDULE,
                default => null
            },
            'succeed' => $data['statistic']['success'],
            'failed' => $data['statistic']['failure'],
            'cost' => $data['statistic']['cost'],
        ], $execution);

        $task = $this->task->find($task_id);
        if ($task !== null) {
            $this->task->set(['status' => TASK_STATUS_STOPPED], $task);
        }

        return response()->json();
    }

    public function succeed(ExecutionSucceedRequest $request): JsonResponse
    {
        $task_id = $request->validated('process_id');
        $execution = $this->execution->getRunningByTaskId($task_id);
        if ($execution === null) {
            abort(400, __('messages.execution.not_exists', ['task_id' => $task_id]));
        }

        $this->execution->countUp('succeed', $execution);

        return response()->json();
    }

    public function failed(ExecutionFailedRequest $request): JsonResponse
    {
        $task_id = $request->validated('process_id');
        $execution = $this->execution->getRunningByTaskId($task_id);
        if ($execution === null) {
            abort(400, __('messages.execution.not_exists', ['task_id' => $task_id]));
        }

        $this->execution->countUp('failed', $execution);

        return response()->json();
    }
}
