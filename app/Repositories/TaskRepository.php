<?php

namespace App\Repositories;

use App\Models\Task;
use App\Services\ProcessService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TaskRepository
{
    protected ProcessService $process;

    public function __construct(ProcessService $process)
    {
        $this->process = $process;
    }

    /**
     * Query
     * @param array $conditions
     * @param array $with
     * @return Builder
     */
    protected function query(array $conditions = [], array $with = []): Builder
    {
        $query = Task::query();

        if (isset($conditions['name'])) {
            $query->where('name', 'like', '%'.$conditions['name'].'%');
        }
        if (isset($conditions['link'])) {
            $query->where('link', 'like', '%'.$conditions['link'].'%');
        }
        if (isset($conditions['plan_id'])) {
            $query->where('plan_id', $conditions['plan_id']);
        }
        if (isset($conditions['start_date_from'])) {
            $query->where('start_date', '>=', to_date($conditions['start_date_from']));
        }
        if (isset($conditions['start_date_to'])) {
            $start_date_to = to_date($conditions['start_date_to']);
            if ($start_date_to instanceof \DateTimeInterface) {
                $query->where('start_date', '<', $start_date_to->addDay());
            } else {
                $query->where('start_date', '<=', $start_date_to);
            }
        }
        if (isset($conditions['status'])) {
            switch ($conditions['status']) {
                case TASK_STATUS_STOPPED:
                    $query->whereIn('status', [TASK_STATUS_STOPPED, TASK_STATUS_STOPPING]);
                    break;

                case TASK_STATUS_RUNNING:
                    $query->whereIn('status', [TASK_STATUS_RUNNING, TASK_STATUS_STARTUP]);
                    break;

                default:
                    $query->where('status', $conditions['status']);
            }
        }

        if (!empty($with)) {
            $query->with($with);
        }

        return $query;
    }

    /**
     * タスクリストを取得
     * @param array $conditions
     * @param array $with
     * @param int|null $per_page
     * @return Collection<Task>|LengthAwarePaginator<Task>
     */
    public function list(array $conditions = [], array $with = [], int $per_page = null): Collection|LengthAwarePaginator
    {
        $query = $this
            ->query($conditions, $with)
            ->orderByDesc('created_at');

        if ($per_page !== null) {
            return $query->paginate($per_page);
        }

        return $query->get();
    }

    /**
     * 起動可能なタスクを取得
     * @param array|null $id_list
     * @return Collection
     */
    public function listForStartUp(array $id_list = null): Collection
    {
        $query = $this->query(with: ['plan'])
            ->whereNotNull('plan_id')
            ->whereIn('status', [TASK_STATUS_STOPPED, TASK_STATUS_PREPARING])
            ->where(function (Builder $query) {
                $query
                    ->orWhereNull('start_time')
                    ->orWhere(function (Builder $query) {
                        $query
                            ->whereNotNull('start_time')
                            ->where('start_time', '<=', now());
                    });
            })
            ->where(function (Builder $query) {
                $query
                    ->orWhereNull('end_time')
                    ->orWhere(function (Builder $query) {
                        $query
                            ->whereNotNull('end_time')
                            ->where('end_time', '>', now());
                    });
            });

        if ($id_list !== null) {
            $query->whereIn('id', $id_list);
        }

        return $query->get();
    }

    /**
     * 有効可能なタスクを取得
     * @param array|null $id_list
     * @return Collection
     */
    public function listForActive(array $id_list = null): Collection
    {
        $query = $this->query()
            ->whereNotNull('plan_id')
            ->where('status', TASK_STATUS_STOPPED);

        if ($id_list !== null) {
            $query->whereIn('id', $id_list);
        }

        return $query->get();
    }

    /**
     * 停止可能なタスクを取得
     * @param array|null $id_list
     * @return Collection
     */
    public function listForStop(array $id_list = null): Collection
    {
        $query = $this->query()
            ->whereIn('status', [TASK_STATUS_RUNNING, TASK_STATUS_PREPARING]);

        if ($id_list !== null) {
            $query->whereIn('id', $id_list);
        }

        return $query->get();
    }

    /**
     * 実行可能な準備中のタスクを取得
     * @return Collection<Task>
     */
    public function listForScheduleStart(): Collection
    {
        return Task::query()
            ->where('status', TASK_STATUS_PREPARING)
            ->whereNotNull('start_time')
            ->where('start_time', '<=', now())
            ->where(function (Builder $query) {
                $query
                    ->orWhereNull('end_time')
                    ->orWhere('end_time', '>', now());
            })
            ->whereNotNull('plan_id')
            ->get();
    }

    /**
     * IDでタスク情報取得
     * @param int $id
     * @param array $conditions
     * @return Task|null
     */
    public function find(int $id, array $conditions = []): ?Model
    {
        return $this->query($conditions)->find($id);
    }

    /**
     * タスク情報更新
     * @param array $data
     * @param Task|null $task
     * @return Task
     */
    public function set(array $data, Task $task = null): Task
    {
        if ($task === null) {
            $task = new Task();
        }

        foreach ($data as $field => $value) {
            if ($task->isFillable($field)) {
                $task->setAttribute($field, $value);
            }
        }

        $task->save();

        return $task;
    }

    /**
     * IDで停止状態のタスクを削除
     * @param array $id_list
     * @return void
     */
    public function deleteStopped(array $id_list): void
    {
        Task::query()
            ->whereIn('id', $id_list)
            ->where('status', TASK_STATUS_STOPPED)
            ->delete();
    }

    /**
     * タスク起動
     * @param Collection<Task> $tasks
     * @return array<int, int> task_id => task_status
     */
    public function startup(Collection $tasks): array
    {
        $task_id_list = $tasks->pluck('id');

        Task::query()
            ->whereIn('id', $task_id_list)
            ->update(['status' => TASK_STATUS_STARTUP]);

        // 起動リクエスト送信
        $task_list = $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'link' => $task->link,
                'plan' => $task->plan->content,
                'end_time' => $task->end_time,
            ];
        })->toArray();

        $results = $this->process->requestStartUp($task_list);

        // 失敗したタスクの状態を戻す
        $origin_tasks = $tasks->keyBy('id');
        $failed_tasks = Task::query()
            ->whereIn('id', collect($results)->reject()->keys())
            ->get();

        foreach ($failed_tasks as $task) {
            $origin_status = $origin_tasks->get($task->id, $task)->status;
            $task->status = $origin_status;
            $task->save();
        }

        return $failed_tasks
            ->pluck('status', 'id')
            ->union($task_id_list->mapWithKeys(fn($id) => [$id => TASK_STATUS_STARTUP]))
            ->toArray();
    }

    /**
     * タスクの状態を「準備中」に変更
     * @param Collection $tasks
     * @return array<int, int> task_id => task_status
     */
    public function active(Collection $tasks): array
    {
        $task_id_list = $tasks->pluck('id');

        Task::query()
            ->whereIn('id', $task_id_list)
            ->whereNotNull('plan_id')
            ->whereNotIn('status', [TASK_STATUS_RUNNING, TASK_STATUS_STARTUP])
            ->update(['status' => TASK_STATUS_PREPARING]);

        return $task_id_list
            ->mapWithKeys(fn($id) => [$id => TASK_STATUS_PREPARING])
            ->toArray();
    }

    /**
     * タスク停止
     * @param Collection $tasks
     * @return array<int, int> task_id => task_status
     */
    public function stop(Collection $tasks): array
    {
        $task_id_list = $tasks->pluck('id');

        Task::query()
            ->whereIn('id', $task_id_list)
            ->where('status', TASK_STATUS_PREPARING)
            ->update(['status' => TASK_STATUS_STOPPED]);

        // 停止リクエスト送信
        $stopping_id_list = Task::query()
            ->whereIn('id', $task_id_list)
            ->where('status', '!=', TASK_STATUS_STOPPED)
            ->pluck('id');

        $result = $this->process->requestStop($stopping_id_list->toArray());

        if ($result) {
            Task::query()
                ->whereIn('id', $stopping_id_list)
                ->update(['status' => TASK_STATUS_STOPPING]);
        }

        return $stopping_id_list
            ->mapWithKeys(fn($id) => [$id => TASK_STATUS_STOPPING])
            ->union($task_id_list->mapWithKeys(fn($id) => [$id => TASK_STATUS_STOPPED]))
            ->toArray();
    }
}
