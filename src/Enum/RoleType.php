<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum RoleType: string implements TranslatableInterface
{
    case Renter = 'renter';
    case Owner = 'owner';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        // Translate enum using custom labels
        return match ($this) {
            self::Renter => $translator->trans('user.type.renter', locale: $locale),
            self::Owner  => $translator->trans('user.type.owner', locale: $locale),
        };
    }
}