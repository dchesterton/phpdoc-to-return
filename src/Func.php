<?php
namespace CSD\PhpDocToReturn;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Func
{
    /**
     * @var ReturnComment
     */
    private $returnComment = null;

    /**
     * @var \ReflectionFunctionAbstract
     */
    private $reflection;

    /**
     * @var File
     */
    private $file;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @param \ReflectionFunctionAbstract $reflection
     * @param File                        $file
     */
    public function __construct(\ReflectionFunctionAbstract $reflection, File $file)
    {
        $this->reflection = $reflection;
        $this->file = $file;
        $this->parser = new Parser;
    }

    /**
     * @return ReturnComment|false
     */
    public function getReturnComment()
    {
        if (null === $this->returnComment) {
            $this->returnComment = $this->parser->parseDocComment($this->reflection);
        }

        return $this->returnComment;
    }

    /**
     * @return \ReflectionFunctionAbstract
     */
    public function getReflection()
    {
        return $this->reflection;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }
}
