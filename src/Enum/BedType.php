<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum BedType: string implements TranslatableInterface
{
    case Individual = 'individual';
    case Double = 'double';
    case Other = 'other';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        // Translate enum using custom labels
        return match ($this) {
            self::Individual  => $translator->trans('room.bed.individual', locale: $locale),
            self::Double => $translator->trans('room.bed.double', locale: $locale),
            self::Other  => $translator->trans('room.bed.other', locale: $locale),
        };
    }
}