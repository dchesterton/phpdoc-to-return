<?php
namespace CSD\PhpdocToReturn;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 */
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

    /**
     * @param bool $writeObjectArrayReturnType
     *
     * @return $this
     */
    public function setWriteObjectArrayReturnType($writeObjectArrayReturnType)
    {
        $this->writeObjectArrayReturnType = $writeObjectArrayReturnType;
        return $this;
    }

    /**
     * @param bool $removeRedundantDocComments
     *
     * @return $this
     */
    public function setRemoveRedundantDocComments($removeRedundantDocComments)
    {
        $this->removeRedundantDocComments = $removeRedundantDocComments;
        return $this;
    }

    /**
     * Run the application to convert files.
     */
    public function run()
    {
        $converter = new Converter;
        $converter->setRemoveRedundantDocComments($this->removeRedundantDocComments);
        $converter->setWriteObjectArrayReturnType($this->writeObjectArrayReturnType);

        foreach ($this->getIterator() as $fileinfo) {
            $fileName = $fileinfo[0];

            $file = new File($fileName);
            $converter->convert($file);

            if (0 === strpos($fileName, $this->sourceFolder)) {
                $path = substr($fileName, strlen($this->sourceFolder));

                $destinationFile = $this->destinationFolder . $path;

                $destinationFolder = pathinfo($destinationFile, PATHINFO_DIRNAME);

                if (!file_exists($destinationFolder)) {
                    mkdir($destinationFolder, 0777, true);
                }

                file_put_contents($destinationFile, $file->getCode());
            }
        }
    }
}
