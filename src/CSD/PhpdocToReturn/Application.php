<?php

namespace CSD\PhpdocToReturn;

use CSD\PhpdocToReturn\Converter;

class Application
{
    /**
     * @var string
     */
    private $sourceFolder;

    /**
     * @var string
     */
    private $destinationFolder;

    /**
     * @var bool
     */
    private $writeObjectArrayReturnType = true;

    /**
     * @var bool
     */
    private $removeRedundantDocComments = true;

    /**
     * @param $source
     * @param $destination
     */
    public function __construct($source, $destination)
    {
        $this->sourceFolder = $source;
        $this->destinationFolder = $destination;
    }

    /**
     * @return \RegexIterator
     */
    public function getIterator()
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->sourceFolder));
        $regexIterator = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        return $regexIterator;
    }

    /**
     * @return string
     */
    public function getSourceFolder()
    {
        return $this->sourceFolder;
    }

    /**
     * @return string
     */
    public function getDestinationFolder()
    {
        return $this->destinationFolder;
    }

    public function run()
    {
        $converter = new Converter;
        $converter->setRemoveRedundantDocComments($this->removeRedundantDocComments);
        $converter->setWriteObjectArrayReturnType($this->writeObjectArrayReturnType);

        foreach ($this->getIterator() as $fileinfo) {
            $filename = $fileinfo[0];

            $file = new File($filename);
            $converter->convert($file);

            if (0 === strpos($filename, $this->sourceFolder)) {
                $path = substr($filename, strlen($this->sourceFolder));

                $destinationFile = $this->destinationFolder . $path;

                $destinationFolder = pathinfo($destinationFile, PATHINFO_DIRNAME);

                if (!file_exists($destinationFolder)) {
                    mkdir($destinationFolder, 0777, true);
                }

                file_put_contents($destinationFile, $file->getCode());
            }
        }
    }

    /**
     * @param boolean $writeObjectArrayReturnType
     *
     * @return $this
     */
    public function setWriteObjectArrayReturnType($writeObjectArrayReturnType)
    {
        $this->writeObjectArrayReturnType = $writeObjectArrayReturnType;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getRemoveRedundantDocComments()
    {
        return $this->removeRedundantDocComments;
    }

    /**
     * @param boolean $removeRedundantDocComments
     *
     * @return $this
     */
    public function setRemoveRedundantDocComments($removeRedundantDocComments)
    {
        $this->removeRedundantDocComments = $removeRedundantDocComments;
        return $this;
    }
}
