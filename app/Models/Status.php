<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
    ];

    protected $casts = [
        'color' => 'string',
    ];

    /**
     * Get the todos for this status.
     */
    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }
}
