<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'period',
        'note',
        'description',
        'months',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function priceLabel(): string
    {
        $label = 'Rp' . number_format($this->price, 0, ',', '.') . '/' . $this->period;
        if (!empty($this->note)) {
            $label .= ' (' . $this->note . ')';
        }
        return $label . '.';
    }

    public function expiryFromToday(): string
    {
        return Carbon::today()->addMonths($this->months)->format('Y-m-d');
    }
}
