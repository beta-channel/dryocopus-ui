<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user {user_id} {password} {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ユーザーアカウントの作成';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $user_id = $this->argument('user_id');
        $password = $this->argument('password');
        $username = $this->argument('username');

        if (User::withTrashed()->where('user_id', $user_id)->exists()) {
            $this->error('User id <'.$user_id.'> already exists!');
            return self::FAILURE;
        }

        $user = new User();
        $user->user_id = $user_id;
        $user->password = $password;
        $user->name = $username;
        $user->save();

        $this->info('User id <'.$user_id.'> created successfully!');

        return self::SUCCESS;
    }
}
