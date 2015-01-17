<?php

namespace CSD\PhpdocToReturn\ReturnType;

interface ReturnTypeInterface
{
    /**
     * @return string
     */
    public function getDeclaration();

    /**
     * @return bool
     */
    public function isDocCommentRedundant();
}