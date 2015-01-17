<?php
namespace CSD\PhpdocToReturn;

use CSD\PhpdocToReturn\ReturnType\Parser;

class Func
{
    private $returnType = null;

    /**
     * @var \ReflectionFunctionAbstract
     */
    private $reflection;

    /**
     * @var File
     */
    private $file;

    /**
     * @param \ReflectionFunctionAbstract $reflection
     * @param File                        $file
     */
    public function __construct(\ReflectionFunctionAbstract $reflection, File $file)
    {
        $this->reflection = $reflection;
        $this->file = $file;
    }

    /**
     * @return ReturnType\ReturnTypeInterface
     */
    public function getReturnType()
    {
        if (null === $this->returnType) {
            $docComment = $this->reflection->getDocComment();

            // no comment at all
            if ($docComment && preg_match('/\*\s*@return\s([^\s]+)/', $docComment, $matches)) {
                $parser = new Parser;
                $this->returnType = $parser->parse($matches[1]);
            } else {
                $this->returnType = false;
            }
        }

        return $this->returnType;
    }

    public function hasReturnDoc()
    {
        return (bool) $this->getReturnType();
    }

    /**
     * @return \ReflectionFunctionAbstract
     */
    public function getReflection()
    {
        return $this->reflection;
    }

    /**
     * @param \ReflectionFunctionAbstract $reflection
     *
     * @return $this
     */
    public function setReflection($reflection)
    {
        $this->reflection = $reflection;
        return $this;
    }

    /**
     * @return \CSD\PhpdocToReturn\File
     */
    public function getFile()
    {
        return $this->file;
    }
}