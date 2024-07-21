<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanCreateRequest;
use App\Http\Requests\PlanDeleteRequest;
use App\Repositories\PlanRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    protected PlanRepository $plan;

    public function __construct(PlanRepository $plan)
    {
        $this->plan = $plan;
    }

    public function index(Request $request): View
    {
        $plans = $this->plan->list(
            ['keyword' => $request->get('keyword')],
            ['tasks'],
            config('app.per_page')
        );

        return view('plan.index', [
            'plans' => $plans,
        ]);
    }

    /**
     * プランを作成
     * @param PlanCreateRequest $request
     * @return JsonResponse|RedirectResponse
     */
    public function create(PlanCreateRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();
        $plan = $this->plan->set($data);

        if ($request->ajax()) {
            return response()->json([
                'id' => $plan->id,
                'name' => $plan->name,
            ]);
        }

        return redirect()
            ->route('plans')
            ->with('notify.success', __('messages.plan.create.success'));
    }

    /**
     * プラン削除
     * @param PlanDeleteRequest $request
     * @return RedirectResponse
     */
    public function delete(PlanDeleteRequest $request): RedirectResponse
    {
        $plan_id_list = $request->validated('id_list', []);
        $this->plan->delete($plan_id_list);

        return redirect()
            ->route('plans')
            ->with('notify.success', __('messages.plan.delete.success'));
    }
}
