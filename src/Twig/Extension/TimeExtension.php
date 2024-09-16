<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
/**
 * Extension Twig pour filtrer l affichage des dates
 */
class TimeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('time_diff', [$this, 'getTimeDiff']), // extension time_diff execute [ce code]
        ];
    }

    public function getTimeDiff(\DateTimeInterface $date): string
    {
        $now = new \DateTimeImmutable();
        $diff = $date->diff($now);

        if ($diff->d > 0) {
            return $diff->y . ' yaer' . ($diff->d > 1 ? 's' : ''); // property fait partie objet de diff
        }

        if ($diff->d > 0) {
            return $diff->m . ' month' . ($diff->d > 1 ? 's' : '');
        }

        if ($diff->d > 0) {
            return $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
        }

        if ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '');
        }

        return $diff->i . ' min' . ($diff->i > 1 ? 's' : '');
    }
}
