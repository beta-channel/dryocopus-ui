<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskBatchOperationRequest;
use App\Http\Requests\TaskSetRequest;
use App\Repositories\PlanRepository;
use App\Repositories\TaskRepository;
use App\Services\SpotService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TaskController extends Controller
{
    protected TaskRepository $task;

    protected PlanRepository $plan;

    protected SpotService $spot;

    public function __construct(TaskRepository $task, PlanRepository $plan, SpotService $spot)
    {
        $this->task = $task;
        $this->plan = $plan;
        $this->spot = $spot;
    }

    public function index(Request $request): View
    {
        $tasks = $this->task->list([
            'name' => $request->query('name'),
            'link' => $request->query('link'),
            'plan_id' => $request->query('plan'),
            'status' => $request->query('status'),
        ], ['plan'], config('app.per_page'));

        $plans = $this->plan->list();

        return view('task.index', [
            'tasks' => $tasks,
            'plans' => $plans,
        ]);
    }

    public function edit(Request $request, ?int $task_id = null): View
    {
        if ($task_id !== null) {
            $task = $this->task->find($task_id);
            abort_if($task === null, 404);
        }

        $plans = $this->plan->list();

        return view('task.edit', [
            'task' => $task ?? null,
            'plans' => $plans,
        ]);
    }

    public function update(TaskSetRequest $request, ?int $task_id = null): RedirectResponse
    {
        $data = $request->validated();

        if ($task_id !== null) {
            $task = $this->task->find($task_id);
            if ($task === null) {
                return redirect()
                    ->route('tasks')
                    ->with('notify.error', __('messages.task.update.not_exist'));
            }

            // 有効中のタスクは編集不可
            if ($task->status !== TASK_STATUS_STOPPED) {
                return redirect()
                    ->route('tasks')
                    ->with('notify.error', __('messages.task.update.impossible', ['status' => __('messages.task_status.'.$task->status)]));
            }

            $task = $this->task->set($data, $task);
        } else {
            $task = $this->task->set($data);
        }

        return redirect()
            ->route('tasks')
            ->with('notify.success', __('messages.task.'.($task->wasRecentlyCreated ? 'create' : 'update').'.success'));
    }

    public function detail(Request $request, int $task_id): View
    {
        $task = $this->task->find($task_id);
        abort_if($task === null, 404);

        return view('task.detail', [
            'task' => $task,
        ]);
    }

    /**
     * タスクを削除
     * @param TaskBatchOperationRequest $request
     * @return RedirectResponse
     */
    public function delete(TaskBatchOperationRequest $request): RedirectResponse
    {
        $task_id_list = $request->validated('id_list', []);
        $this->task->deleteStopped($task_id_list);

        return redirect()
            ->route('tasks')
            ->with('notify.success', __('messages.task.delete.success'));
    }

    /**
     * タスク起動
     * @param TaskBatchOperationRequest $request
     * @return JsonResponse
     */
    public function startup(TaskBatchOperationRequest $request): JsonResponse
    {
        $task_id_list = $request->validated('id_list', []);
        $tasks = $this->task->listForStartUp($task_id_list);
        if ($tasks->isNotEmpty()) {
            $result = $this->task->startup($tasks);
        } else {
            $result = [];
        }

        return response()->json(
            Arr::map($result, fn($status) => $this->getResponseTaskStatus($status))
        );
    }

    /**
     * タスクを有効にする
     * @param TaskBatchOperationRequest $request
     * @return JsonResponse
     */
    public function active(TaskBatchOperationRequest $request): JsonResponse
    {
        $task_id_list = $request->validated('id_list', []);
        $tasks = $this->task->listForActive($task_id_list);
        if ($tasks->isNotEmpty()) {
            $result = $this->task->active($tasks);
        } else {
            $result = [];
        }

        return response()->json(
            Arr::map($result, fn($status) => $this->getResponseTaskStatus($status))
        );
    }

    /**
     * タスクを停止
     * @param TaskBatchOperationRequest $request
     * @return JsonResponse
     */
    public function stop(TaskBatchOperationRequest $request): JsonResponse
    {
        $task_id_list = $request->validated('id_list', []);
        $tasks = $this->task->listForStop($task_id_list);
        if ($tasks->isNotEmpty()) {
            $result = $this->task->stop($tasks);
        } else {
            $result = [];
        }

        return response()->json(
            Arr::map($result, fn($status) => $this->getResponseTaskStatus($status))
        );
    }

    protected function getResponseTaskStatus(int $status)
    {
        return Arr::get([
            TASK_STATUS_STARTUP => 'startup',
            TASK_STATUS_RUNNING => 'running',
            TASK_STATUS_PREPARING => 'preparing',
            TASK_STATUS_STOPPING => 'stopping',
            TASK_STATUS_STOPPED => 'stopped',
        ], $status);
    }
}
