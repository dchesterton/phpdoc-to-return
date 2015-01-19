<?php

namespace CSD\PhpdocToReturn\ReturnType;

class ThisType extends SelfType
{
    /**
     * @return bool
     */
    public function isDocCommentRedundant()
    {
        return false;
    }
}
