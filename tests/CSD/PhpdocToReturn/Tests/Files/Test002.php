<?php
/**
 * Test multiple namespaces in the same file
 */

namespace CSD\PhpdocToReturn\Tests\Files;

class Test002
{
    /**
     * @return \DateTime Comment
     */
    public function test()
    {

    }
}

namespace CSD\PhpdocToReturn\Tests\Files2;

class Test002
{
    /**
     * @return \DateTime
     */
    public function test()
    {

    }
}