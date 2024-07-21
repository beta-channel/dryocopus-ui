<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 100)->unique()->comment('ユーザーID');
            $table->string('password', 255)->comment('パスワード');
            $table->string('name', 100)->nullable()->comment('ユーザー名');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->comment('ユーザー');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
