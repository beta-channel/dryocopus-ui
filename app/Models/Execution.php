<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * 実行履歴
 * @property int $id
 * @property int $task_id FK.タスクID
 * @property string $task_name タスク名称
 * @property string $link リンク
 * @property array $plan プラン
 * @property Carbon $start_time 開始時間
 * @property Carbon $finish_time 終了時間
 * @property int $succeed 成功回数
 * @property int $failed 失敗回数
 * @property int $finished_as 終了原因
 * @property float $cost 運用料金
 * @property string $note
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class Execution extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'executions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_name',
        'link',
        'plan',
        'start_time',
        'finish_time',
        'succeed',
        'failed',
        'finished_as',
        'cost',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:'.config('app.format.datetime'),
            'finish_time' => 'datetime:'.config('app.format.datetime'),
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    protected function plan(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => json_decode($value, true),
            set: fn($value) => is_array($value) ? json_encode($value, JSON_NUMERIC_CHECK) : $value
        );
    }
}
