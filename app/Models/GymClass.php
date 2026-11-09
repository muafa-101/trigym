<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GymClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'description',
        'trainer',
        'days',
        'start_time',
        'end_time',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments', 'class_id', 'user_id');
    }

    public function timeLabel(): string
    {
        return $this->start_time . ' - ' . $this->end_time . ' WIB';
    }
}
