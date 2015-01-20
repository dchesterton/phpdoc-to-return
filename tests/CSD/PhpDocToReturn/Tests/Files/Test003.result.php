<?php
/**
 * Test different keywords on methods.
 */

namespace CSD\PhpDocToReturn\Tests\Files;

abstract class Test003
{
    abstract public function test(): \DateTime;

    private function test2(): \DateTime
    {

    }

    protected function test3(): \DateTime
    {

    }

    final protected function test4(): \DateTime
    {

    }

    function &test5(): \DateTime
    {

    }

    static function test6(): \DateTime
    {

    }
}