<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BackupLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'file_name',
        'file_size_bytes',
    ];

    protected function casts(): array
    {
        return [
            'file_size_bytes' => 'integer',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
