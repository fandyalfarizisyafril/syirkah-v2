<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    public const STATUSES = ['new', 'contacted', 'qualified', 'closed', 'spam'];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['consent_at' => 'datetime', 'notified_at' => 'datetime'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
