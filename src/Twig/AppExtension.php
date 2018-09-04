<?php

/*
 * This file is part of Sorterings-ABC.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('html_entity_decode', [$this, 'htmlEntityDecode']),
        ];
    }

    public function htmlEntityDecode($string)
    {
        return html_entity_decode($string, ENT_QUOTES | ENT_HTML5);
    }
}
