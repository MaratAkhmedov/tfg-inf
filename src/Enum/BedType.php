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
        // Translate enum from name (Left, Center or Right)
        return $translator->trans($this->name, locale: $locale);

        // Translate enum using custom labels
        return match ($this) {
            self::Individual  => $translator->trans('room.form.bed.type.individual', locale: $locale),
            self::Double => $translator->trans('room.form.bed.type.double', locale: $locale),
            self::Other  => $translator->trans('room.form.bed.type.other', locale: $locale),
        };
    }
}