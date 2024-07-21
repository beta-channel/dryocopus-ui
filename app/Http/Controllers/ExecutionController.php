<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExecutionFinishRequest;
use App\Http\Requests\ExecutionStartRequest;
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
        $data = $request->validated();

        $task = $this->task->find($data['task_id']);
        if ($task === null) {
            abort(400, __('messages.task.not_exists', ['task_id' => $data['task_id']]));
        }
        if (in_array($task->status, [TASK_STATUS_RUNNING, TASK_STATUS_STOPPING])) {
            abort(400, __('messages.task.already_running'));
        }

        $execution = DB::transaction(function () use ($data, $task) {
            $this->task->set(['status' => TASK_STATUS_RUNNING], $task);
            return $this->execution->create($data);
        });

        return response()->json(['id' => $execution->id]);
    }

    /**
     * 実行停止
     * @param ExecutionFinishRequest $request
     * @return JsonResponse
     */
    public function finish(ExecutionFinishRequest $request): JsonResponse
    {
        $data = $request->validated();

        $execution = $this->execution->find($data['id']);
        if ($execution === null) {
            abort(400, __('messages.execution.not_exists', ['execution_id' => $data['id']]));
        }

        $data['finish_time'] = to_date($data['finish_time'], config('app.format.datetime'), false);
        $data['finished_as'] = match ($data['reason']) {
            'error' => EXECUTION_FINISHED_AS_ERROR,
            'normal' => EXECUTION_FINISHED_AS_NORMAL,
            'schedule' => EXECUTION_FINISHED_AS_SCHEDULE,
            default => null
        };

        $this->execution->update($data, $execution);
        $task = $this->task->find($data['task_id']);
        if ($task !== null) {
            $this->task->set(['status' => TASK_STATUS_STOPPED], $task);
        }

        return response()->json();
    }
}
