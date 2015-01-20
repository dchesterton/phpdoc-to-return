<?php
namespace CSD\PhpDocToReturn\ReturnType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 */
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
     * {@inheritdoc}
     */
    public function getDeclaration($hack)
    {
        if ($hack && $this->type) {
            return sprintf('array<%s>', $this->type);
        }

        return 'array';
    }

    /**
     * {@inheritdoc}
     */
    public function isDocCommentRedundant()
    {
        if ($this->type) {
            return false;
        }

        return true;
    }
}
