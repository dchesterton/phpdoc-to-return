<?php
namespace CSD\PhpdocToReturn;

class Parser
{
    /**
     * @param \ReflectionFunctionAbstract $function
     *
     * @return ReturnDeclaration|false
     */
    public function parseDocComment(\ReflectionFunctionAbstract $function)
    {
        $docComment = $function->getDocComment();

        if (!$docComment) {
            return false;
        }

        if (preg_match('/\*\s*@return\s(.*)/i', $docComment, $matches)) {
            return $this->parseReturnString($matches[1], $function);
        } elseif ($function instanceof \ReflectionMethod && preg_match('/\{@inheritdoc\}/i', $docComment, $matches)) {
            /** @var \ReflectionMethod $function */
            $class = $function->getDeclaringClass();

            foreach ($class->getInterfaces() as $interface) {
                $result = $this->parseClassMethod($interface, $function->getName());

                if (false !== $result) {
                    return $result;
                }
            }

            if ($parent = $class->getParentClass()) {
                $result = $this->parseClassMethod($parent, $function->getName());

                if (false !== $result) {
                    return $result;
                }
            }
        }
        return false;
    }

    private function parseClassMethod(\ReflectionClass $class, $method)
    {
        if ($class->hasMethod($method)) {
            $result = $this->parseDocComment($class->getMethod($method));

            if ($result) {
                return $result;
            }
        }

        return false;
    }

    private function isUnsupported($string)
    {
        return in_array($string, [
            'string', 'int', 'integer', 'float', 'bool', 'boolean', 'resource', 'null',
            'true', 'false', 'void', 'null', 'mixed', 'object'
        ]);
    }

    /**
     * @param string $returnString
     * @param \ReflectionFunctionAbstract $function
     *
     * @return ReturnDeclaration|false
     */
    private function parseReturnString($returnString, \ReflectionFunctionAbstract $function)
    {
        $parts = explode(' ', $returnString);
        $type = $parts[0];

        unset($parts[0]);

        $comment = implode(' ', $parts);

        if ($this->isUnsupported($type)) {
            return false;
        }

        if ('array' == $type) {
            return new ReturnDeclaration(new ReturnType\ArrayType, $comment);
        }

        if ('callable' == $type) {
            return new ReturnDeclaration(new ReturnType\CallableType, $comment);
        }

        if ('$this' == $type) {
            if ($function instanceof \ReflectionMethod) {
                return new ReturnDeclaration(new ReturnType\ThisType, $comment);
            }
            return false; // shouldn't have functions with @return $this, but bail out if we do
        }

        if ('self' == $type) {
            return new ReturnDeclaration(new ReturnType\SelfType, $comment);
        }

        // cannot support multiple return types
        if (false !== strpos($type, '|')) {
            return false;
        }

        // array of
        if ('[]' === substr($type, -2)) {
            $object = substr($type, 0, strlen($type) - 2);

            return new ReturnDeclaration(new ReturnType\ArrayType($object), $comment);
        }


        return new ReturnDeclaration(new ReturnType\ClassType($type), $comment);
    }
}