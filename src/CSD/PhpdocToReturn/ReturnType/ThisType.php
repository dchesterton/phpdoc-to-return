<?php

namespace CSD\PhpdocToReturn\ReturnType;

class ThisType implements ReturnTypeInterface
{
    /**
     * @return string
     */
    public function getDeclaration()
    {
        return 'self';
    }

    /**
     * @return bool
     */
    public function isDocCommentRedundant()
    {
        return false;
    }
}
