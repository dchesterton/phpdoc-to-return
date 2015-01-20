<?php
namespace CSD\PhpDocToReturn\Tests;

use CSD\PhpDocToReturn\Converter;
use CSD\PhpDocToReturn\File;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    public function getFiles()
    {
        return [
            ['001'], ['002'], ['003']
        ];
    }

    /**
     * @dataProvider getFiles
     */
    public function testPhpFileConversion($number)
    {
        $original = $this->getOriginalFile($number);
        $phpResult = $this->getPhpFile($number);

        $converter = new Converter;
        $file = new File($original);
        $converter->convert($file, false);

        $this->assertEquals(file_get_contents($phpResult), $file->getCode());
    }

    /**
     * @dataProvider getFiles
     */
    public function testHackFileConversion($number)
    {
        $original = $this->getOriginalFile($number);

        $hackResult = $this->getHackFile($number);
        $hackResult = file_exists($hackResult)? $hackResult: $this->getPhpFile($number);

        $converter = new Converter;

        $file = new File($original);
        $converter->convert($file, true);

        $this->assertEquals(file_get_contents($hackResult), $file->getCode());
    }

    private function getOriginalFile($number)
    {
        return __DIR__ . '/Files/Test' . $number . '.php';
    }

    private function getPhpFile($number)
    {
        return __DIR__ . '/Files/Test' . $number . '.result.php';
    }

    private function getHackFile($number)
    {
        return __DIR__ . '/Files/Test' . $number . '.result.hh';
    }
}