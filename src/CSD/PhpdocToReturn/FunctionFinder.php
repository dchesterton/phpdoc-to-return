<?php

namespace CSD\PhpdocToReturn;

class FunctionFinder
{
    /**
     * @var string
     */
    private $root;

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param string $root
     *
     * @return $this
     */
    public function setRoot($root)
    {
        $this->root = $root;
        return $this;
    }

    /**
     * @return \RegexIterator
     */
    public function getIterator()
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->root));
        $regexIterator = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        return $regexIterator;
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        $files = [];

        foreach ($this->getIterator() as $fileinfo) {
            $files[] = new File($fileinfo[0]);
        }

        return $files;
    }
}
