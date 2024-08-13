<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ProcessService
{
    protected string $endpoint = "https://endpoint.dryocopus.jp/api/v1";

    /**
     * 起動リクエスト
     * @param array<array> $tasks
     * @return array<string, bool>
     */
    public function requestStartUp(array $tasks): array
    {
        try {
            $response = $this->setupHttpClient(Http::asJson())->post('/startup', [
                'tasks' => Arr::map($tasks, function ($task) {
                    return [
                        'process_id' => (string)$task['id'],
                        'url' => $task['link'],
                        'plan' => json_encode($task['plan']),
                        'schedule' => [
                            'finish_time' => format_date($task['end_time'], 'Y-m-d H:i:s'),
                        ],
                    ];
                }),
                'callbacks' => [
                    'on_started' => route('execution.start'),
                    'on_stopped' => route('execution.finish'),
                    'on_success' => route('execution.succeed'),
                    'on_failure' => route('execution.failed'),
                ]
            ]);
        } catch (ConnectionException) {
            return $this->startupAllFailed($tasks);
        }

        if (!$response->successful()) {
            return $this->startupAllFailed($tasks);
        }

        $succeed = Arr::mapWithKeys($response['data']['success'], function ($task_id) {
            return [$task_id => true];
        });
        $failed = Arr::mapWithKeys($response['data']['failure'], function ($task_id) {
            return [$task_id => false];
        });
        $processing = Arr::mapWithKeys($response['data']['processing'], function ($task_id) {
            return [$task_id => true];
        });

        return array_merge($failed, $processing, $succeed);
    }

    protected function startupAllFailed(array $tasks): array
    {
        return Arr::mapWithKeys($tasks, function ($task) {
            return [$task['id'] => false];
        });
    }

    /**
     * 停止リクエスト
     * @param array $task_id_list
     * @return bool
     */
    public function requestStop(array $task_id_list): bool
    {
        try {
            $response = $this->setupHttpClient(Http::asJson())->post('/stop', [
                'tasks' => Arr::map($task_id_list, function ($task_id) {
                    return ['process_id' => (string)$task_id];
                }),
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
            ->baseUrl($this->endpoint)
            ->acceptJson()
            ->timeout(30)
            ->retry(3, throw: false)
            ->withHeader('X-DRYOCOPUS-KEY', config('dryocopus.key'));
    }
}
