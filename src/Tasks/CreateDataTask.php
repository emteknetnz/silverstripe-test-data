<?php

namespace emteknetnz\TestData\Tasks;

use SilverStripe\Dev\BuildTask;
use emteknetnz\TestData\Pages\PageA;
use emteknetnz\TestData\Blocks\BlockA;
use emteknetnz\TestData\Utils\Util;
use SilverStripe\ORM\DB;

class CreateDataTask extends BuildTask
{
    // very noticable degredation in performance when increasing from 500 to 1000
    const PAGES = 500;
    const BLOCK_AS = 10;

    private static $segment = 'CreateDataTask';

    public function run($request)
    {
        $this->deleteStuff();
        $this->createStuff();
    }

    private function createStuff()
    {
        for ($i = 0; $i < self::PAGES; $i++) {
            $pageA = PageA::create();
            $pageA->Title = "Page A $i";
            $pageA->write();
            $elementalAreaID = $pageA->ElementalAreaID;
            for ($j = 0; $j < self::BLOCK_AS; $j++) {
                $blockA = BlockA::create();
                $blockA->Title = "Block A $i $j";
                $blockA->Content = Util::loremIpsum();
                $blockA->ParentID = $elementalAreaID;
                $blockA->write();
            }
            // greatly slows down task
            // $pageA->publishRecursive();
        }
    }

    /**
     * Using TRUNCATE rather that DataList::removeAll() for performance
     */
    private function deleteStuff()
    {
        foreach (['', '_Live'] as $suffix) {
            foreach ([
                // generic tables
                'SiteTree',
                'Element',
                // model tables
                'PageA',
                'BlockA'
            ] as $table) {
                DB::query("TRUNCATE TABLE \"$table$suffix\"");
            }
        }
    }
}
