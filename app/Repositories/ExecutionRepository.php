<?php

namespace App\Repositories;

use App\Models\Execution;
use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ExecutionRepository
{
    /**
     * Query
     * @param array $conditions
     * @return Builder
     */
    protected function query(array $conditions = []): Builder
    {
        $query = Execution::query();

        if (isset($conditions['task_name'])) {
            $query->where('task_name', 'like', '%'.$conditions['task_name'].'%');
        }
        if (isset($conditions['link'])) {
            $query->where('link', 'like', '%'.$conditions['link'].'%');
        }
        if (isset($conditions['link_name'])) {
            $query->where(function (Builder $query) use ($conditions) {
                $query
                    ->orWhere('link', 'like', '%'.$conditions['link_name'].'%')
                    ->orWhere('task_name', 'like', '%'.$conditions['link_name'].'%');
            });
        }
        if (isset($conditions['finished'])) {
            $query->whereNull('finish_time', not: (bool)$conditions['finished']);
        }

        return $query;
    }

    /**
     * 実行履歴リストを取得
     * @param array $conditions
     * @param int|null $per_page
     * @return Collection<Task>|LengthAwarePaginator<Task>
     */
    public function list(array $conditions = [], int $per_page = null): Collection|LengthAwarePaginator
    {
        $query = $this
            ->query($conditions)
            ->orderByDesc('created_at');

        if ($per_page !== null) {
            return $query->paginate($per_page);
        }

        return $query->get();
    }

    /**
     * IDで実行履歴を取得
     * @param int $id
     * @return Execution|null
     */
    public function find(int $id): ?Model
    {
        return Execution::query()->find($id);
    }

    /**
     * 実行履歴を作成
     * @param array $data
     * @return Execution
     */
    public function create(array $data): Execution
    {
        $execution = new Execution();
        $execution->task_name = $data['task_name'];
        $execution->link = $data['link'];
        $execution->plan = $data['plan'];
        $execution->start_time = to_date($data['start_time'], config('app.format.datetime'), false);
        $execution->save();
        return $execution;
    }

    /**
     * 実行履歴を更新
     * @param array $data
     * @param Execution $execution
     * @return Execution
     */
    public function update(array $data, Execution $execution): Execution
    {
        foreach ($data as $field => $value) {
            if ($execution->isFillable($field)) {
                $execution->setAttribute($field, $value);
            }
        }

        $execution->save();

        return $execution;
    }
}
