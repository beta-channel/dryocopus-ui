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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20)->nullable()->comment('名称');
            $table->string('link', 255)->comment('リンク');
            $table->unsignedBigInteger('plan_id')->nullable()->comment('FK.プランID');
            $table->dateTime('start_time')->nullable()->comment('開始時間');
            $table->dateTime('end_time')->nullable()->comment('終了時間');
            $table->tinyInteger('status')->default(0)->comment('ステータス - [0].停止 [1].起動中 [2].実行中 [3]停止中 [9]準備中');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('plan_id')->references('id')->on('plans');

            $table->comment('タスク');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
