<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * プラン
 * @property int $id
 * @property string $name プラン名
 * @property array $content プラン内容
 * @property string $note
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Collection<Task> $tasks
 */
class Plan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'content',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'plan_id');
    }

    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => json_decode($value, true),
            set: fn($value) => is_array($value) ? json_encode($value, JSON_NUMERIC_CHECK) : $value
        );
    }

    public static function isValidPlan($content): bool
    {
        return is_array($content) && collect($content)->every(function ($schedule) {
                return isset($schedule['time']) && isset($schedule['interval'])
                    && is_array($schedule['time']) && is_array($schedule['interval'])
                    && count($schedule['time']) === 2 && count($schedule['interval']) === 2
                    && is_string($schedule['time'][0]) && is_string($schedule['time'][1])
                    && preg_match('/^\d\d:\d\d$/', $schedule['time'][0]) && preg_match('/^\d\d:\d\d$/', $schedule['time'][1])
                    && is_int($schedule['interval'][0]) && is_int($schedule['interval'][1]);
            });
    }
}
