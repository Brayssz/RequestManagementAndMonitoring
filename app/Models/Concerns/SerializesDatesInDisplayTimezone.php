<?php

namespace App\Models\Concerns;

use DateTimeInterface;
use Illuminate\Support\Carbon;

/**
 * Serializes model dates in the display timezone instead of UTC.
 *
 * Timestamps stay stored in UTC. This only affects how they are written into
 * JSON responses: the value carries an explicit offset (e.g. +08:00) so the
 * browser parses the exact instant rather than guessing.
 */
trait SerializesDatesInDisplayTimezone
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return Carbon::instance($date)
            ->setTimezone(config('app.display_timezone'))
            ->toIso8601String();
    }
}
