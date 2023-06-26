<?php

namespace emteknetnz\TestData\Tasks;

use SilverStripe\Dev\BuildTask;
use emteknetnz\TestData\Pages\PageA;
use emteknetnz\TestData\Blocks\BlockA;
use emteknetnz\TestData\Utils\Util;
use SilverStripe\ORM\DB;

class CreateDataTask extends BuildTask
{
    const NUM_PAGE_LEVELS = 3;

    // 1 PAGE_LEVELS
    const PAGES_01 = 1000;

    // 2 PAGE_LEVELS
    const PAGES_02_01 = 10;
    const PAGES_02_02 = 100;

    // 3 Page_Levels
    const PAGES_03_01 = 10;
    const PAGES_03_02 = 10;
    const PAGES_03_03 = 10;

    // this is about the average number of blocks for projects
    const BLOCK_AS = 5;

    private static $segment = 'CreateDataTask';

    public function run($request)
    {
        $this->deleteStuff();
        if (self::NUM_PAGE_LEVELS == 1) {
            $this->createStuffNumPageLevelsOne();
        } elseif (self::NUM_PAGE_LEVELS == 2) {
            $this->createStuffNumPageLevelsTwo();
        } elseif (self::NUM_PAGE_LEVELS == 3) {
            $this->createStuffNumPageLevelsThree();
        }
    }

    private function createStuffNumPageLevelsOne()
    {
        for ($i = 0; $i < self::PAGES_01; $i++) {
            $pageA01 = PageA::create();
            $pageA01->Title = "Page A 01 $i";
            $pageA01->write();
            $elementalAreaID = $pageA01->ElementalAreaID;
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

    private function createStuffNumPageLevelsTwo()
    {
        for ($i = 0; $i < self::PAGES_02_01; $i++) {
            $pageA01 = PageA::create();
            $pageA01->Title = "Page A 01 $i";
            $pageA01->write();
            for ($j = 0; $j < self::PAGES_02_02; $j++) {
                $pageA02 = PageA::create();
                $pageA02->Title = "Page A 02 $i $j";
                $pageA02->ParentID = $pageA01->ID;
                $pageA02->write();
                $elementalAreaID = $pageA02->ElementalAreaID;
                for ($k = 0; $k < self::BLOCK_AS; $k++) {
                    $blockA = BlockA::create();
                    $blockA->Title = "Block A $i $j $k";
                    $blockA->Content = Util::loremIpsum();
                    $blockA->ParentID = $elementalAreaID;
                    $blockA->write();
                }
            }
            // greatly slows down task
            // $pageA->publishRecursive();
        }
    }

    private function createStuffNumPageLevelsThree()
    {
        for ($i = 0; $i < self::PAGES_03_01; $i++) {
            $pageA01 = PageA::create();
            $pageA01->Title = "Page A 01 $i";
            $pageA01->write();
            for ($j = 0; $j < self::PAGES_03_02; $j++) {
                $pageA02 = PageA::create();
                $pageA02->Title = "Page A 02 $i $j";
                $pageA02->ParentID = $pageA01->ID;
                $pageA02->write();
                for ($k = 0; $k < self::PAGES_03_02; $k++) {
                    $pageA03 = PageA::create();
                    $pageA03->Title = "Page A 03 $i $j";
                    $pageA03->ParentID = $pageA02->ID;
                    $pageA03->write();
                    $elementalAreaID = $pageA03->ElementalAreaID;
                    for ($l = 0; $l < self::BLOCK_AS; $l++) {
                        $blockA = BlockA::create();
                        $blockA->Title = "Block A $i $j $k";
                        $blockA->Content = Util::loremIpsum();
                        $blockA->ParentID = $elementalAreaID;
                        $blockA->write();
                    }
                }
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
        foreach (['', '_Live', '_Versions'] as $suffix) {
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
