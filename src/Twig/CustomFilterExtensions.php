<?php

namespace App\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Contracts\Translation\TranslatorInterface;

class CustomFilterExtensions extends AbstractExtension
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('time_ago', [$this, 'timeAgo']),
        ];
    }

    public function timeAgo(\DateTimeInterface $dateTime)
    {
        $now = new DateTime();
        $interval = $now->diff($dateTime);

        if ($interval->y > 0) {
            return $this->translator->trans(
                'time_ago',
                ['unit' => 'year', 'count' => $interval->y],
                'messages+intl-icu'
            );
        }
        if ($interval->m > 0) {
            return $this->translator->trans(
                'time_ago',
                ['unit' => 'month', 'count' => $interval->m],
                'messages+intl-icu'
            );
        }
        if ($interval->d > 0) {
            return $this->translator->trans(
                'time_ago',
                ['unit' => 'day', 'count' => $interval->d],
                'messages+intl-icu'
            );
        }
        if ($interval->h > 0) {
            return $this->translator->trans(
                'time_ago',
                ['unit' => 'hour', 'count' => $interval->h],
                'messages+intl-icu'
            );
        }
        if ($interval->i > 0) {
            return $this->translator->trans(
                'time_ago',
                ['unit' => 'minute', 'count' => $interval->i],
                'messages+intl-icu'
            );
        }

        return $this->translator->trans(
            'time_ago',
            ['unit' => 'second', 'count' => $interval->s],
            'messages+intl-icu'
        );
    }
}
