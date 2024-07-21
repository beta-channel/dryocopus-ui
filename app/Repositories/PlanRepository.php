<?php

namespace App\Repositories;

use App\Models\Plan;
use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlanRepository
{
    /**
     * Query
     * @param array $conditions
     * @param array $with
     * @return Builder
     */
    protected function query(array $conditions = [], array $with = []): Builder
    {
        $query = Plan::query();

        if (isset($conditions['keyword'])) {
            foreach (explode(' ', Str::replace('　', ' ', $conditions['keyword'])) as $word) {
                $query->where('name', 'like', "%{$word}%");
            }
        }
        if (isset($conditions['name'])) {
            $query->where('name', 'like', '%'.$conditions['name'].'%');
        }

        if (!empty($with)) {
            $query->with($with);
        }

        return $query;
    }

    /**
     * プランリストを取得
     * @param array $conditions
     * @param array $with
     * @param int|null $per_page
     * @return Collection<Plan>|LengthAwarePaginator<Plan>
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
     * IDでプランを取得
     * @param int $id
     * @param array $conditions
     * @return Plan|null
     */
    public function find(int $id, array $conditions = []): ?Model
    {
        return $this->query($conditions)->find($id);
    }

    /**
     * プラン情報更新
     * @param array $data
     * @param Plan|null $plan
     * @return Plan
     */
    public function set(array $data, Plan $plan = null): Plan
    {
        if ($plan === null) {
            $plan = new Plan();
        }

        foreach ($data as $field => $value) {
            if ($plan->isFillable($field)) {
                $plan->setAttribute($field, $value);
            }
        }

        $plan->save();

        return $plan;
    }

    public function delete(array $id_list, bool $inactivated_only = true): void
    {
        DB::transaction(function () use ($id_list, $inactivated_only) {
            $query = Plan::query()->whereIn('id', $id_list);
            if ($inactivated_only) {
                $query->whereDoesntHave('tasks', function (Builder $query) {
                    $query->where('active', true);
                });
            }

            Task::withTrashed()
                ->whereIn('plan_id', $query->pluck('id'))
                ->update(['plan_id' => null]);

            $query->delete();
        });
    }
}
