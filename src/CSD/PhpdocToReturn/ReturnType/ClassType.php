<?php

namespace CSD\PhpdocToReturn\ReturnType;

class ClassType implements ReturnTypeInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getDeclaration()
    {
        return $this->className;
    }

    /**
     * @return bool
     */
    public function isDocCommentRedundant()
    {
        return true;
    }
}
