<?php

namespace App\Support;

/** Tingkat keramaian gym berdasarkan jumlah check-in hari ini. */
class GymStatus
{
    public function __construct(public readonly int $checkinsToday)
    {
    }

    public function label(): string
    {
        return match (true) {
            $this->checkinsToday < 10 => 'Sepi',
            $this->checkinsToday < 30 => 'Normal',
            default                   => 'Ramai',
        };
    }

    public function bannerClass(): string
    {
        return match ($this->label()) {
            'Sepi'   => 'bg-[#22A94F]',
            'Normal' => 'bg-[#F2A900]',
            default  => 'bg-[#FF4444]',
        };
    }

    public function badgeClass(): string
    {
        return match ($this->label()) {
            'Sepi'   => 'bg-[#14602B]',
            'Normal' => 'bg-[#7A5600]',
            default  => 'bg-[#6B2222]',
        };
    }

    public function summary(): string
    {
        return number_format($this->checkinsToday, 0, ',', '.') . ' orang check in hari ini';
    }
}
