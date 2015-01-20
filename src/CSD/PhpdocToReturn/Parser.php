<?php
namespace CSD\PhpdocToReturn;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 */
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
        } elseif ($function instanceof \ReflectionMethod && false !== strpos($docComment, '{@inheritdoc}')) {
            /** @var \ReflectionMethod $function */
            $class = $function->getDeclaringClass();

            foreach ($class->getInterfaces() as $interface) {
                $result = $this->parseClassMethod($interface, $function->getName());

                if (false !== $result) {
                    return $result;
                }
            }

            if ($parent = $class->getParentClass()) {
                return $this->parseClassMethod($parent, $function->getName());
            }
        }
        return false;
    }

    /**
     * @param \ReflectionClass $class
     * @param string           $method
     *
     * @return bool|ReturnDeclaration
     */
    private function parseClassMethod(\ReflectionClass $class, $method)
    {
        if ($class->hasMethod($method)) {
            return $this->parseDocComment($class->getMethod($method));
        }

        return false;
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

        if (in_array($type, ['int', 'integer'])) {
            return new ReturnDeclaration(new ReturnType\ScalarType('int'), $comment);
        }

        if (in_array($type, ['string', 'mixed', 'void', 'float', 'resource'])) {
            return new ReturnDeclaration(new ReturnType\ScalarType($type), $comment);
        }

        if (in_array($type, ['bool', 'boolean', 'true', 'false'])) {
            return new ReturnDeclaration(new ReturnType\ScalarType('bool'), $comment);
        }

        // unsupported types, todo: check if supported in Hack?
        if (in_array($type, ['null', 'object'])) {
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
            // todo: check for string|null, \DateTime|null etc. and add support for Hack nullable types
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
