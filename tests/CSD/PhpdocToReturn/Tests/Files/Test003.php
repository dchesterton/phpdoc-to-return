<?php
/**
 * Test different keywords on methods.
 */

namespace CSD\PhpdocToReturn\Tests\Files;

abstract class Test003
{
    /**
     * @return \DateTime
     */
    abstract public function test();

    /**
     * @return \DateTime
     */
    private function test2()
    {

    }

    /**
     * @return \DateTime
     */
    protected function test3()
    {

    }

    /**
     * @return \DateTime
     */
    final protected function test4()
    {

    }

    /**
     * @return \DateTime
     */
    function &test5()
    {

    }

    /**
     * @return \DateTime
     */
    static function test6()
    {

    }
}