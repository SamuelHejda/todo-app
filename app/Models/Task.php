<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'completed',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    public function tags(): BelongsToMany
{
    return $this->belongsToMany(Tag::class, 'task_tag');
}
}