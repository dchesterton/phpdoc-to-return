<?php
namespace CSD\PhpdocToReturn;

class Func
{
    /**
     * @var ReturnDeclaration
     */
    private $returnDeclaration = null;

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
     * @return ReturnDeclaration|false
     */
    public function getReturnDeclaration()
    {
        if (null === $this->returnDeclaration) {
            $this->returnDeclaration = $this->parser->parseDocComment($this->reflection);
        }

        return $this->returnDeclaration;
    }

    /**
     * @return bool
     */
    public function hasReturnDoc()
    {
        return (bool) $this->getReturnDeclaration();
    }

    /**
     * @return \ReflectionFunctionAbstract
     */
    public function getReflection()
    {
        return $this->reflection;
    }

    /**
     * @return \CSD\PhpdocToReturn\File
     */
    public function getFile()
    {
        return $this->file;
    }
}