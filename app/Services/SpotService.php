<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SpotService
{
    /**
     * 起動リクエスト
     * @param array<array> $tasks
     * @return array<string, bool>
     */
    public function requestStartUp(array $tasks): array
    {
        $responses = Http::pool(fn(Pool $pool) => Arr::map(
            $tasks,
            function ($task) use ($pool) {
                return $this
                    ->setupHttpClient($pool->as((string)$task['id']))
                    ->post('/startup', $task);
            })
        );

        return Arr::map($responses, function (Response $response) {
            return $response->successful();
        });
    }

    /**
     * 停止リクエスト
     * @param array $task_id_list
     * @param int $reason
     * @return bool
     */
    public function requestStop(array $task_id_list, int $reason): bool
    {
        try {
            $response = $this->setupHttpClient(Http::asJson())->post('/stop', [
                'id_list' => $task_id_list,
                'reason' => $reason,
            ]);
        } catch (ConnectionException) {
            return false;
        }

        return $response->successful();
    }

    /**
     * Httpセットアップ
     * @param PendingRequest $http
     * @return PendingRequest
     */
    protected function setupHttpClient(PendingRequest $http): PendingRequest
    {
        return $http
            ->baseUrl($this->endpoint())
            ->acceptJson()
            ->timeout(30)
            ->retry(3, throw: false);
    }

    protected function endpoint(): string
    {
        $endpoint = env('SPOT_ENDPOINT');
        if ($endpoint === null) {
            abort(500, 'Spot endpoint not set!');
        }

        return Str::chopEnd($endpoint, '/');
    }
}
