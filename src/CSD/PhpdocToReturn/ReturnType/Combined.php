<?php
namespace CSD\PhpdocToReturn\ReturnType;

class Combined implements ReturnTypeInterface
{
    /**
     * @var array
     */
    private $returns;

    public function __construct(array $returns)
    {
        $this->returns = $returns;
    }

    /**
     * @return string
     */
    public function getDeclaration()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isDocCommentRedundant()
    {
        return false;
    }
}