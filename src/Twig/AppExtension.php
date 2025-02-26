<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;


class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_date', [$this, 'formatDate'], ['is_safe' => ['html']]),
        ];
    }

    public function formatDate(\DateTimeInterface $date, string $format = 'd/m/Y  (H:i)'): string
    {
        if($date === null) {
            return '';
        }
        return $date->format($format);
    }
}
