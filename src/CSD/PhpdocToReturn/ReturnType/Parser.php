<?php
namespace CSD\PhpdocToReturn\ReturnType;

class Parser
{
    private function isPrimitive($string)
    {
        return in_array($string, [
            'string', 'int', 'integer', 'float', 'bool', 'boolean', 'resource', 'null', 'callable',
            'true', 'false', 'void', 'null', 'mixed'
        ]);
    }

    public function parse($returnString)
    {
        if ($this->isPrimitive($returnString)) {
            return false;
        }

        if ('array' == $returnString) {
            return new ArrayType;
        }

        if ('object' == $returnString) {
            return new ObjectType;
        }

        if (false !== strpos($returnString, '|')) {
            $parts = explode('|', $returnString);

            $combinedParts = [];

            foreach ($parts as $part) {
                $combinedParts[] = $this->parse($part);
            }

            return new Combined($combinedParts);
        }

        if ('[]' === substr($returnString, -2)) {
            $object = substr($returnString, 0, strlen($returnString) - 2);

            return new ArrayType($object);
        }

        if ('$this' == $returnString) {
            return new ThisType;
        }

        return new ClassType($returnString);
    }
}