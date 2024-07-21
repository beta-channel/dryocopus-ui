<?php
// タスク状態
const TASK_STATUS_STOPPED = 0;  // 停止
const TASK_STATUS_STARTUP = 1;  // 起動中
const TASK_STATUS_RUNNING = 2;  // 実行中
const TASK_STATUS_STOPPING = 3;  // 停止中
const TASK_STATUS_PREPARING = 9;  // 準備中
// 実行終了原因
const EXECUTION_FINISHED_AS_ERROR = 0;  // 異常終了
const EXECUTION_FINISHED_AS_NORMAL = 1;  // 実行終了
const EXECUTION_FINISHED_AS_SCHEDULE = 2;  // スケジュール終了
