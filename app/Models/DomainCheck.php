<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $domain_id
 * @property int|null $status_code
 * @property int|null $response_time
 * @property bool $is_success
 * @property bool|null $content_matched
 * @property string|null $error
 * @property Carbon $created_at
 *
 * @property-read Domain $domain
 */
class DomainCheck extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'domain_id',
        'status_code',
        'response_time',
        'is_success',
        'content_matched',
        'error',
        'created_at',
    ];

    protected $casts = [
        'is_success' => 'boolean',
        'content_matched' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }
}
