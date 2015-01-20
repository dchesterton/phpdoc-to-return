<?php
namespace CSD\PhpdocToReturn\ReturnType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 */
interface ReturnTypeInterface
{
    /**
     * Return the PHP Return Type code.
     *
     * @param bool $hack
     *
     * @return string|false
     */
    public function getDeclaration($hack);

    /**
     * Is the doc comment redundant if this return type is added?
     *
     * @return bool
     */
    public function isDocCommentRedundant();
}
