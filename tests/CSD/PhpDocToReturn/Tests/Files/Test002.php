<?php
/**
 * Test multiple namespaces in the same file
 */

namespace CSD\PhpDocToReturn\Tests\Files;

class Test002
{
    /**
     * @return \DateTime Comment
     */
    public function test()
    {

    }
}

namespace CSD\PhpDocToReturn\Tests\Files2;

class Test002
{
    /**
     * @return \DateTime
     */
    public function test()
    {

    }
}