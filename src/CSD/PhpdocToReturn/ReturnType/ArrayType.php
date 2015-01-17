<?php

namespace CSD\PhpdocToReturn\ReturnType;

class ArrayType implements ReturnTypeInterface
{
    /**
     * @var null|string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct($type = null)
    {
        $this->type = $type;
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDeclaration()
    {
        return 'array';
    }

    /**
     * @return bool
     */
    public function isDocCommentRedundant()
    {
        if ($this->type) {
            return false;
        }

        return true;
    }
}
