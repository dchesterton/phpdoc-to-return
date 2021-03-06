<?php
/**
 * Basic conversion tests.
 */

namespace CSD\PhpDocToReturn\Tests\Files ;

class Test001
{
    public function testNoComment()
    {}

    /**
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * @return void
     */
    public function __destruct()
    {

    }

    /**
     * @return self
     */
    public function __clone()
    {

    }

    /**
     * @return \DateTime
     */
    public function testClass()
    {
        return new \DateTime;
    }

    /**
     * A long description of what this method does.
     *
     * And a bit more.
     *
     * @param string $param1 Desc of parameter 1
     * @param string $param2 Desc of parameter 2
     *
     * @return \DateTime
     */
    public function testWithLongDocComment($param1, $param2)
    {
    }

    /**
     * A long description of what this method does.
     *
     * And a bit more.
     *
     * @param string $param1 Desc of parameter 1
     * @param string $param2 Desc of parameter 2
     *
     * @return int
     */
    public function testWithLongDocCommentInt($param1, $param2)
    {
    }

    /**
     * A long description of what this method does.
     *
     * And a bit more.
     *
     * @param string $param1 Desc of parameter 1
     * @param string $param2 Desc of parameter 2
     *
     * @return \DateTime A comment
     */
    public function testWithLongDocCommentWithReturnComment($param1, $param2)
    {
    }

    public function testControlStructures()
    {
        $x = '';
        $test = "{$x}";

        $str = <<<HEREDOC
A string with a {$test} variable inside of it.
HEREDOC;

        if (1 == 2)
            return true;

        if (1 == 2) {
            return false;
        }

        $func = function ($func) use ($test) {

        };
    }
}

/**
 * @return int
 */
function return_int_001()
{
    return 1;
}

/**
 * @return float
 */
function return_float_001()
{
    return 1.1;
}