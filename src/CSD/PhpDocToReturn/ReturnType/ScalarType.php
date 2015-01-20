<?php
namespace CSD\PhpDocToReturn\ReturnType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 */
class ScalarType implements ReturnTypeInterface
{
    /**
     * @var string
     */
    private $scalar;

    /**
     * @param string $scalar
     */
    public function __construct($scalar)
    {
        $this->scalar = $scalar;
    }

    /**
     * {@inheritdoc}
     */
    public function getDeclaration($hack)
    {
        return $hack? $this->scalar: false;
    }

    /**
     * {@inheritdoc}
     */
    public function isDocCommentRedundant()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getScalar()
    {
        return $this->scalar;
    }
}
