<?php

namespace App\Console\Commands;

use App\Repositories\TaskRepository;
use Illuminate\Console\Command;

class ScheduleExecuteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '有効な予約タスクを実行する';

    /**
     * Execute the console command.
     */
    public function handle(TaskRepository $taskRepository): void
    {
        $tasks = $taskRepository->listForScheduleStart();
        $taskRepository->startup($tasks);
    }
}
