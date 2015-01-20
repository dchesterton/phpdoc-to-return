<?php
namespace CSD\PhpDocToReturn\ReturnType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
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
     * {@inheritdoc}
     */
    public function getDeclaration($hack)
    {
        return $this->className;
    }

    /**
     * {@inheritdoc}
     */
    public function isDocCommentRedundant()
    {
        return true;
    }
}
