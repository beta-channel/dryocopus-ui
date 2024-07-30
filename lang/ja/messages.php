<?php

return [
    'error' => 'エラーが発生しました！',
    'password' => [
        'change' => [
            'success' => 'パスワードを変更しました'
        ]
    ],
    'task' => [
        'create' => [
            'success' => '新しいタスクを作成しました！'
        ],
        'update' => [
            'success' => 'タスクを更新しました！',
            'impossible' => ':statusのタスクなのでを更新できない！',
            'not_exist' => '該当タスクは存在しないなので更新できません！'.PHP_EOL.'すでに削除された可能性があります。'
        ],
        'delete' => [
            'success' => 'タスクを削除しました！',
            'fail' => 'タスクの削除が失敗しました！',
        ],
        'not_exists' => 'タスクID:task_idは存在しません！',
        'already_running' => 'タスクID:task_idはすでに実行しています！',
    ],
    'plan' => [
        'create' => [
            'success' => '新しいプランを作成しました！',
        ],
        'update' => [
            'success' => 'プランを更新しました！',
        ],
        'delete' => [
            'success' => 'プランを削除しました！',
        ],
    ],
    'execution' => [
        'not_exists' => '実行中なタスクID:task_idは存在しません！',
    ],
    #######################
    ######## Texts ########
    #######################
    'task_status' => [
        TASK_STATUS_STOPPED => '停止',
        TASK_STATUS_STARTUP => '起動中',
        TASK_STATUS_RUNNING => '実行中',
        TASK_STATUS_STOPPING => '停止中',
        TASK_STATUS_PREPARING => '準備中',
    ],
    'execution_finished_as' => [
        EXECUTION_FINISHED_AS_ERROR => 'エラー',
        EXECUTION_FINISHED_AS_NORMAL => '手動終了',
        EXECUTION_FINISHED_AS_SCHEDULE => '日程終了',
    ],
];
