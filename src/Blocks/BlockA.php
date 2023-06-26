<?php

namespace emteknetnz\TestData\Blocks;

use DNADesign\Elemental\Models\BaseElement;

class BlockA extends BaseElement
{
    private static $db = [
        'Content' => 'HTMLText'
    ];

    private static $table_name = 'BlockA';

    private static $singular_name = 'block-a';

    private static $plural_name = 'block-as';

    public function getType()
    {
        return 'Block A';
    }
}
