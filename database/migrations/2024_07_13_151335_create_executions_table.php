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
        Schema::create('executions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id')->comment('FK.タスクID');
            $table->string('task_name', 20)->nullable()->comment('タスク名称');
            $table->string('link',255)->comment('リンク');
            $table->json('plan')->comment('プラン');
            $table->dateTime('start_time')->comment('開始時間');
            $table->dateTime('finish_time')->nullable()->comment('終了時間');
            $table->tinyInteger('finished_as')->nullable()->comment('終了原因 - [0].異常終了 [1].実行終了 [2].スケジュール終了');
            $table->unsignedInteger('succeed')->default(0)->comment('成功回数');
            $table->unsignedInteger('failed')->default(0)->comment('失敗回数');
            $table->decimal('cost', 10, 2)->nullable()->comment('運用料金');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('task_id')->references('id')->on('tasks');

            $table->comment('実行履歴');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('executions');
    }
};
