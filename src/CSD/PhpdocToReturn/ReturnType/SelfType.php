<?php

namespace CSD\PhpdocToReturn\ReturnType;

class SelfType implements ReturnTypeInterface
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
        return true;
    }
}
