<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Daniel Chesterton
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
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
