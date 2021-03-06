<?php
namespace CSD\PhpDocToReturn;

use CSD\PhpDocToReturn\ReturnType\ReturnTypeInterface;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
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
