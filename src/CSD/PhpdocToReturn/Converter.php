<?php
namespace CSD\PhpdocToReturn;

use CSD\PhpdocToReturn\ReturnType\ArrayType;

class Converter
{
    private $writeObjectArrayReturnType = true;

    public function shouldWriteReturnType(Func $function)
    {
        $type = $function->getReturnType();

        if ($type && $type->getDeclaration()) {
            if ($type instanceof ArrayType && $type->getType()) {
                return $this->writeObjectArrayReturnType;
            }

            return true;
        }

        return false;
    }

    public function convert(Func $function)
    {
        if (!$this->shouldWriteReturnType($function)) {
            return false;
        }

        $returnType = $function->getReturnType();

        $declaration = ': ' . $returnType->getDeclaration();

        $file = $function->getFile();

        $startToken = $file->getStartTokenForFunction($function);
        $tokens = $file->getTokens();

        for ($i = $startToken; $i < count($tokens); $i++) {
            if ($tokens[$i] == '{' || $tokens[$i] == ';') {
                $prev = $tokens[$i - 1];

                if (is_array($prev) && $prev[0] == T_WHITESPACE) {
                    $file->insertToken($declaration, $i - 1);
                } else {
                    $file->insertToken($declaration, $i);
                }

                break;
            }
        }

        if ($returnType->isDocCommentRedundant()) {
            for ($i = $startToken - 1; $i >= 0; $i--) {

                $token = $tokens[$i];

                if (is_array($token)) {
                    if (in_array($token[0], [T_WHITESPACE, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_ABSTRACT, T_FINAL])) {
                        continue;
                    }

                    if ($token[0] == T_DOC_COMMENT) {
                        $token[1] = preg_replace('/\n\s*\*\s*@return\s.*/im', '', $token[1]);

                        if (preg_match('#^/\*\*[\s|\*]*\*/$#', $token[1])) {
                            $token[1] = '';
                        }

                        $file->replaceToken($token, $i);
                    }

                    break;
                }
            }
        }


        //$tokens = $file->getTokens();





        /*
        $contents = file_get_contents($function->getReflection()->getFileName());

        $name = $function->getReflection()->getName();
        $declaration = $function->getReturnType()->getDeclaration();

        $pattern = '/(' . preg_quote($name) . '\(.*\))(\s*)\{/m';

        $replacement = '$1: ' . preg_quote($declaration) . '$2{';

        $contents = preg_replace($pattern, $replacement, $contents);


        var_dump('Adding hint for method: ' . $function->getReflection()->getName());
        var_dump($contents);




        //$declaration = 'function ' . $function->getReflection()->getName() . ': ';
        */

    }

    /**
     * @return boolean
     */
    public function getWriteObjectArrayReturnType()
    {
        return $this->writeObjectArrayReturnType;
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
}