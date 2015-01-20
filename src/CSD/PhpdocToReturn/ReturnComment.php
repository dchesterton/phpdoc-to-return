<?php
namespace CSD\PhpdocToReturn;

use CSD\PhpdocToReturn\ReturnType\ReturnTypeInterface;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 */
class ReturnComment
{
    /**
     * @var ReturnTypeInterface
     */
    private $type;

    /**
     * @var string
     */
    private $comment;

    /**
     * @param ReturnTypeInterface $type
     * @param string              $comment
     */
    public function __construct(ReturnTypeInterface $type, $comment)
    {
        $this->type = $type;
        $this->comment = $comment;
    }

    /**
     * @return ReturnTypeInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
}