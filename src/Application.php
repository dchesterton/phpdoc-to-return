<?php
namespace CSD\PhpDocToReturn;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
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
     * Run the application to convert files.
     *
     * @param bool $hack
     */
    public function run($hack)
    {
        $converter = new Converter;

        foreach ($this->getIterator() as $fileinfo) {
            $fileName = $fileinfo[0];

            $file = new File($fileName);
            $converter->convert($file, $hack);

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
