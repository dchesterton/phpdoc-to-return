<?php
namespace CSD\PhpdocToReturn\Tests;

use CSD\PhpdocToReturn\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Parser
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new Parser;
    }

    public function testFunctionWithNoComment()
    {
        $reflection = new \ReflectionFunction('CSD\PhpdocToReturn\Tests\function_no_comment');
        $this->assertFalse($this->parser->parseDocComment($reflection));
    }

    public function testFunctionWithNoReturn()
    {
        $reflection = new \ReflectionFunction('CSD\PhpdocToReturn\Tests\function_no_return');
        $this->assertFalse($this->parser->parseDocComment($reflection));
    }

    public function testFunctionWithInheritdoc()
    {
        $reflection = new \ReflectionFunction('CSD\PhpdocToReturn\Tests\function_inheritdoc');
        $this->assertFalse($this->parser->parseDocComment($reflection));
    }

    public function testFunctionWithUnsupportedTypes()
    {
        $types = [
            'string', 'int', 'integer', 'float', 'bool', 'boolean', 'resource', 'null',
            'true', 'false', 'void', 'null', 'mixed', 'object'
        ];

        foreach ($types as $type) {
            $reflection = new \ReflectionFunction('CSD\PhpdocToReturn\Tests\function_' . $type);
            $this->assertFalse($this->parser->parseDocComment($reflection));
        }
    }

    public function testFunctionWhichReturnsArray()
    {
        $reflection = new \ReflectionFunction('CSD\PhpdocToReturn\Tests\function_array');

        $decl = $this->parser->parseDocComment($reflection);

        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnDeclaration', $decl);
        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnType\ArrayType', $decl->getType());

        /** @var \CSD\PhpdocToReturn\ReturnType\ArrayType $type */
        $type = $decl->getType();

        $this->assertNull($type->getType());
        $this->assertTrue($decl->getType()->isDocCommentRedundant());
    }

    public function testFunctionWhichReturnsArrayOfObjects()
    {
        $reflection = new \ReflectionFunction('CSD\PhpdocToReturn\Tests\function_array_of_objects');

        $decl = $this->parser->parseDocComment($reflection);

        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnDeclaration', $decl);
        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnType\ArrayType', $decl->getType());

        /** @var \CSD\PhpdocToReturn\ReturnType\ArrayType $type */
        $type = $decl->getType();

        $this->assertEquals('\DateTime', $type->getType());
        $this->assertFalse($decl->getType()->isDocCommentRedundant());
    }

    public function testFunctionWithReturnClass()
    {
        $reflection = new \ReflectionFunction('CSD\PhpdocToReturn\Tests\function_class');

        $decl = $this->parser->parseDocComment($reflection);

        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnDeclaration', $decl);
        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnType\ClassType', $decl->getType());
        $this->assertEmpty($decl->getComment());
    }

    public function testFunctionWhichReturnsThis()
    {
        $reflection = new \ReflectionFunction('CSD\PhpdocToReturn\Tests\function_this');
        $this->assertFalse($this->parser->parseDocComment($reflection));
    }

    public function testFunctionWhichReturnsCallable()
    {
        $reflection = new \ReflectionFunction('CSD\PhpdocToReturn\Tests\function_callable');

        $decl = $this->parser->parseDocComment($reflection);

        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnDeclaration', $decl);
        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnType\CallableType', $decl->getType());
    }

    public function testFunctionWhichReturnsMultipleTypes()
    {
        $reflection = new \ReflectionFunction('CSD\PhpdocToReturn\Tests\function_multiple');

        $this->assertFalse($this->parser->parseDocComment($reflection));
    }

    public function testMethodNoComment()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\TestClass', 'noComment');

        $this->assertFalse($this->parser->parseDocComment($reflection));
    }

    public function testMethodInheritDocNoParents()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\TestClass', 'inheritDoc');

        $this->assertFalse($this->parser->parseDocComment($reflection));
    }

    public function testMethodWhichReturnsClass()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\TestClass', 'returnClass');

        $decl = $this->parser->parseDocComment($reflection);

        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnDeclaration', $decl);
        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnType\ClassType', $decl->getType());
        $this->assertEmpty($decl->getComment());
    }

    public function testMethodWhichReturnsClassWithComment()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\TestClass', 'returnClassWithComment');

        $decl = $this->parser->parseDocComment($reflection);

        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnDeclaration', $decl);
        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnType\ClassType', $decl->getType());
        $this->assertEquals('\DateTime', $decl->getType()->getDeclaration());
        $this->assertEquals('The current date and time', $decl->getComment());
    }

    public function testMethodWithInheritDoc()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\ExtendedTestClass', 'returnClassWithComment');

        $decl = $this->parser->parseDocComment($reflection);

        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnDeclaration', $decl);
        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnType\ClassType', $decl->getType());
        $this->assertEquals('\DateTime', $decl->getType()->getDeclaration());
        $this->assertEquals('The current date and time', $decl->getComment());
    }

    public function testMethodWithInheritDocRecursive()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\ExtendedFurtherTestClass', 'returnClassWithComment');

        $decl = $this->parser->parseDocComment($reflection);

        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnDeclaration', $decl);
        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnType\ClassType', $decl->getType());
        $this->assertEquals('\DateTime', $decl->getType()->getDeclaration());
        $this->assertEquals('The current date and time', $decl->getComment());
    }

    public function testMethodWithInheritDocAndNoParentReturn()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\ExtendedTestClass', 'noComment');

        $this->assertFalse($this->parser->parseDocComment($reflection));
    }

    public function testMethodWithInheritDocWithParentWhichReturnsUnsupportedType()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\ExtendedTestClass', 'returnInt');

        $this->assertFalse($this->parser->parseDocComment($reflection));
    }

    public function testMethodWhichReturnsUnsupportedType()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\TestClass', 'returnInt');

        $this->assertFalse($this->parser->parseDocComment($reflection));
    }

    public function testMethodWhichReturnsThis()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\TestClass', 'returnThis');

        $decl = $this->parser->parseDocComment($reflection);

        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnDeclaration', $decl);
        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnType\ThisType', $decl->getType());
    }

    public function testMethodWhichReturnsSelf()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\TestClass', 'returnSelf');

        $decl = $this->parser->parseDocComment($reflection);

        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnDeclaration', $decl);
        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnType\SelfType', $decl->getType());
    }

    public function testInterfaceMethodWithNoComment()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\TestInterface', 'noComment');

        $this->assertFalse($this->parser->parseDocComment($reflection));
    }

    public function testInterfaceMethodWhichReturnsClass()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\TestInterface', 'returnClass');

        $decl = $this->parser->parseDocComment($reflection);

        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnDeclaration', $decl);
        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnType\ClassType', $decl->getType());
        $this->assertEquals('\DateTime', $decl->getType()->getDeclaration());
    }

    public function testExtendedInterfaceMethodWhichReturnsClass()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\ExtendedTestInterface', 'returnClass');

        $decl = $this->parser->parseDocComment($reflection);

        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnDeclaration', $decl);
        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnType\ClassType', $decl->getType());
        $this->assertEquals('\DateTime', $decl->getType()->getDeclaration());
    }

    public function testClassWithInterfaceMethodWithNoComment()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\TestClassWithInterface', 'noComment');

        $this->assertFalse($this->parser->parseDocComment($reflection));
    }

    public function testClassWithInterfaceMethodWhichReturnsClass()
    {
        $reflection = new \ReflectionMethod('CSD\PhpdocToReturn\Tests\TestClassWithInterface', 'returnClass');

        $decl = $this->parser->parseDocComment($reflection);

        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnDeclaration', $decl);
        $this->assertInstanceOf('CSD\PhpdocToReturn\ReturnType\ClassType', $decl->getType());
        $this->assertEquals('\DateTime', $decl->getType()->getDeclaration());
    }
}


interface TestInterface
{
    public function noComment();

    /**
     * @return \DateTime
     */
    public function returnClass();
}

interface ExtendedTestInterface extends TestInterface
{
    /**
     * {@inheritdoc}
     */
    public function returnClass();
}

class TestClassWithInterface implements TestInterface
{
    /**
     * {@inheritdoc}
     */
    public function noComment()
    {}

    /**
     * {@inheritdoc}
     */
    public function returnClass()
    {}
}

class TestClass
{
    public function noComment()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function inheritDoc()
    {
    }

    /**
     * @return \DateTime
     */
    public function returnClass()
    {
    }

    /**
     * @return \DateTime The current date and time
     */
    public function returnClassWithComment()
    {
    }

    /**
     * @return int
     */
    public function returnInt()
    {
    }

    /**
     * @return $this
     */
    public function returnThis()
    {
    }

    /**
     * @return self
     */
    public function returnSelf()
    {
    }
}

class ExtendedTestClass extends TestClass
{
    /**
     * {@inheritdoc}
     */
    public function returnClassWithComment() {
    }

    /**
     * {@InheritDoc}
     */
    public function noComment()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function returnInt()
    {
    }
}

class ExtendedFurtherTestClass extends ExtendedTestClass
{
    /**
     * {@inheritdoc}
     */
    public function returnClassWithComment() {
    }
}

function function_no_comment()
{}

/**
 * @param $param
 */
function function_no_return($param)
{}

/**
 * {@inheritdoc}
 */
function function_inheritdoc()
{}

/**
 * @return int
 */
function function_int()
{}

/**
 * @return integer
 */
function function_integer()
{}

/**
 * @return bool
 */
function function_bool()
{}

/**
 * @return boolean
 */
function function_boolean()
{}

/**
 * @return string
 */
function function_string()
{}

/**
 * @return true
 */
function function_true()
{}

/**
 * @return false
 */
function function_false()
{}

/**
 * @return resource
 */
function function_resource()
{}

/**
 * @return null
 */
function function_null()
{}

/**
 * @return null
 */
function function_void()
{}

/**
 * @return mixed
 */
function function_mixed()
{}

/**
 * @return object
 */
function function_object()
{}

/**
 * @return float
 */
function function_float()
{}

/**
 * @return \DateTime
 */
function function_class()
{}

/**
 * @return array
 */
function function_array()
{}

/**
 * @return \DateTime[]
 */
function function_array_of_objects()
{}

/**
 * @return $this
 */
function function_this()
{}

/**
 * @return callable
 */
function function_callable()
{}

/**
 * @return \DateTime|\DateTimeZone
 */
function function_multiple()
{}