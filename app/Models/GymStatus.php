<?php

declare(strict_types=1);

namespace TriGym\Models;

/** Tingkat keramaian gym berdasarkan jumlah check-in hari ini. */
final class GymStatus
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

    // Kelas ditulis utuh agar terdeteksi oleh Tailwind CLI.
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
