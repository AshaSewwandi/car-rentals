<?php

namespace App\Support;

class TripNoteBuilder
{
    /**
     * Fold passenger count / stops / final destination into a single free-text
     * note, followed by the author's own message. Used wherever an itinerary
     * (stops + destination) needs to be recorded on a Booking, which has no
     * dedicated columns for that — it stays as descriptive text in `note`.
     *
     * @param string[]|null $stops
     */
    public static function build(
        ?array $stops,
        ?string $finalDestination,
        ?string $message,
        ?int $passengerCount = null
    ): ?string {
        $parts = [];

        if ($passengerCount) {
            $parts[] = 'Passengers: ' . $passengerCount;
        }

        $stopsList = collect($stops ?? [])
            ->map(fn ($stop) => trim((string) $stop))
            ->filter(fn ($stop) => $stop !== '')
            ->values();

        if ($stopsList->isNotEmpty()) {
            $parts[] = 'Stops: ' . $stopsList->implode(', ');
        }

        $finalDestination = trim((string) $finalDestination);
        if ($finalDestination !== '') {
            $parts[] = 'Final destination: ' . $finalDestination;
        }

        $tripSummary = implode(' | ', $parts);
        $message = trim((string) $message);

        if ($tripSummary !== '' && $message !== '') {
            return $tripSummary . "\n" . $message;
        }

        return $tripSummary !== '' ? $tripSummary : ($message !== '' ? $message : null);
    }
}
