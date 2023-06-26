<?php

namespace emteknetnz\TestData\Utils;

use joshtronic\LoremIpsum;

class Util
{
    public static function loremIpsum(): string
    {
        $lipsum = new LoremIpsum();
        $s = $lipsum->paragraphs(5);
        $a = preg_split('/\n\n/', $s);
        return '<p>' . implode('</p><p>', $a) . '</p>';
    }
}
