<?php

namespace App\Models;

use App\Enums\CheckHttpMethod;
use App\Enums\NotifyChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property int $user_id
 * @property string $url
 * @property string|null $name
 * @property int $check_interval
 * @property int $timeout
 * @property CheckHttpMethod $http_method
 * @property string|null $expected_content
 * @property NotifyChannel $notify_channel
 * @property bool $is_active
 * @property int $history_days
 * @property bool|null $last_status
 * @property Carbon|null $last_checked_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 * @property-read Collection<DomainCheck> $checks
 * @property-read DomainCheck|null $lastCheck
 * @property-read int $checks_count
 */
class Domain extends Model
{
    protected $fillable = [
        'url',
        'name',
        'check_interval',
        'timeout',
        'http_method',
        'expected_content',
        'notify_channel',
        'is_active',
        'history_days',
        'last_status',
        'last_checked_at',
    ];

    protected $casts = [
        'http_method' => CheckHttpMethod::class,
        'notify_channel' => NotifyChannel::class,
        'is_active' => 'boolean',
        'last_status' => 'boolean',
        'last_checked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checks(): HasMany
    {
        return $this->hasMany(DomainCheck::class)->latest();
    }

    public function lastCheck(): HasOne
    {
        return $this->hasOne(DomainCheck::class)->latestOfMany('created_at');
    }
}
