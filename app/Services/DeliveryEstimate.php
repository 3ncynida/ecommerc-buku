<?php

namespace App\Services;

use Carbon\CarbonImmutable;

class DeliveryEstimate
{
    public ?float $distanceKm;
    public ?int $durationMinutes;

    public function __construct(?float $distanceKm, ?int $durationMinutes)
    {
        $this->distanceKm = $distanceKm;
        $this->durationMinutes = $durationMinutes;
    }

    public function hasValue(): bool
    {
        return $this->distanceKm !== null && $this->durationMinutes !== null;
    }

    public function formattedDistance(): string
    {
        if (!$this->distanceKm) {
            return 'Tidak tersedia';
        }

        if ($this->distanceKm < 1) {
            return '< 1 km';
        }

        return number_format($this->distanceKm, 1, ',', '.') . ' km';
    }

    public function formattedDuration(): string
    {
        if (!$this->durationMinutes) {
            return 'Tidak tersedia';
        }

        $totalMinutes = $this->durationMinutes;
        $days = intdiv($totalMinutes, 1440);
        $remainder = $totalMinutes % 1440;
        $hours = intdiv($remainder, 60);
        $minutes = $remainder % 60;
        $parts = [];

        if ($days > 0) {
            $parts[] = "{$days} hari";
        }

        if ($hours > 0) {
            $parts[] = "{$hours} jam";
        }

        if ($minutes > 0) {
            $parts[] = "{$minutes} menit";
        }

        return $parts ? implode(' ', $parts) : 'Kurang dari 1 menit';
    }

    public function arrivalAt(): ?CarbonImmutable
    {
        if (!$this->durationMinutes) {
            return null;
        }

        return CarbonImmutable::now()->addMinutes($this->durationMinutes);
    }
}
