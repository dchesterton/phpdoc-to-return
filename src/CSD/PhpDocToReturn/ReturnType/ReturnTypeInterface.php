<?php
namespace CSD\PhpDocToReturn\ReturnType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface ReturnTypeInterface
{
    /**
     * Return the PHP code.
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
