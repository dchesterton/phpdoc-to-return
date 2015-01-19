<?php

namespace CSD\PhpdocToReturn\ReturnType;

class CallableType implements ReturnTypeInterface
{
    /**
     * @return string
     */
    public function getDeclaration()
    {
        return 'callable';
    }

    /**
     * @return bool
     */
    public function isDocCommentRedundant()
    {
        return true;
    }
}
