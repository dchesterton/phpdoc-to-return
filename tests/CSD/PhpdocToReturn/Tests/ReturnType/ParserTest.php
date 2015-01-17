<?php
namespace CSD\PhpdocToReturn\Tests\ReturnType;

use CSD\PhpdocToReturn\ReturnType\ArrayType;
use CSD\PhpdocToReturn\ReturnType\Parser;
use CSD\PhpdocToReturn\ReturnType\PrimitiveType;
use CSD\PhpdocToReturn\ReturnType\VoidType;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParseSimpleArray()
    {
        $parser = new Parser;
        $return = $parser->parse('array');

        $this->assertInstanceOf(ArrayType::class, $return);
        $this->assertEquals(null, $return->getType());
    }

    public function testParseComplexArray()
    {
        $parser = new Parser;
        $return = $parser->parse('MyObject[]');

        $this->assertInstanceOf(ArrayType::class, $return);
        $this->assertEquals('MyObject', $return->getType());
    }

    public function testScalars()
    {
        $scalars = ['string', 'int', 'integer', 'bool', 'boolean', 'float', 'null'];

        foreach ($scalars as $scalar) {
            $parser = new Parser;
            $return = $parser->parse($scalar);

            $this->assertInstanceOf(PrimitiveType::class, $return);
            $this->assertEquals($scalar, $return->getType());
        }
    }

    public function testVoid()
    {
        $parser = new Parser;
        $return = $parser->parse('void');

        $this->assertInstanceOf(VoidType::class, $return);
    }


}
 