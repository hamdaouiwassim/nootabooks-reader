<?php

namespace App\Models;

use App\Models\Concerns\FlushesAppCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory, FlushesAppCache;

    protected $fillable = [
        'text',
        'author',
    ];
}
