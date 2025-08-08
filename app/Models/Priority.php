<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Priority extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'level',
    ];

    protected $casts = [
        'color' => 'string',
        'level' => 'integer',
    ];

    /**
     * Get the todos for this priority.
     */
    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }
}
