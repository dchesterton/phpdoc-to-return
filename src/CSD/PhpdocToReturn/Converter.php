<?php
namespace CSD\PhpdocToReturn;

use CSD\PhpdocToReturn\ReturnType\ArrayType;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 */
class Converter
{
    /**
     * @var bool
     */
    private $writeObjectArrayReturnType = true;

    /**
     * @var bool
     */
    private $removeRedundantDocComments = true;

    /**
     * @var bool
     */
    private $hack = false;

    /**
     * @param Func $function
     *
     * @return bool
     */
    public function shouldWriteReturnType(Func $function)
    {
        $name = $function->getReflection()->getName();

        if ($function->getReflection() instanceof \ReflectionMethod && in_array($name, ['__construct', '__destruct', '__clone'])) {
            return false;
        }

        $declaration = $function->getReturnDeclaration();

        if (!$declaration) {
            return false;
        }

        $type = $declaration->getType();

        if ($type && $type->getDeclaration($this->hack)) {
            if ($type instanceof ArrayType && $type->getType()) {
                return $this->writeObjectArrayReturnType;
            }

            return true;
        }

        return false;
    }

    /**
     * @param File $file
     */
    public function convert(File $file)
    {
        foreach ($file->getFunctions() as $function) {
            $this->convertFunction($function);
        }
    }

    /**
     * @param Func $function
     */
    private function convertFunction(Func $function)
    {
        if (!$this->shouldWriteReturnType($function)) {
            return;
        }

        $returnDeclaration = $function->getReturnDeclaration();

        $returnType = $returnDeclaration->getType();

        $declaration = $returnType->getDeclaration($this->hack);

        if (!$declaration) {
            return;
        }

        $declaration = ': ' . $declaration;

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

        // remove doc comment if it's now redundant
        if (!$returnDeclaration->getComment() && $this->removeRedundantDocComments && $returnType->isDocCommentRedundant()) {
            // work backwards from start of function and try and find the doc comment
            for ($i = $startToken - 1; $i >= 0; $i--) {
                $token = $tokens[$i];

                if (is_array($token)) {
                    // todo: test return by reference
                    if (in_array($token[0], [T_WHITESPACE, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_ABSTRACT, T_FINAL])) {
                        continue;
                    }

                    if ($token[0] == T_DOC_COMMENT) {
                        $token[1] = preg_replace('/\n\s*\*\s*@return\s.*/im', '', $token[1]);

                        // remove empty doc comments
                        if (preg_match('#^/\*\*[\s|\*]*\*/$#', $token[1])) {
                            $token[1] = '';
                        }

                        $file->replaceToken($token, $i);
                    }

                    break;
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function getWriteObjectArrayReturnType()
    {
        return $this->writeObjectArrayReturnType;
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
     * @return bool
     */
    public function getRemoveRedundantDocComments()
    {
        return $this->removeRedundantDocComments;
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
     * @return bool
     */
    public function getHack()
    {
        return $this->hack;
    }

    /**
     * @param bool $hack
     *
     * @return $this
     */
    public function setHack($hack)
    {
        $this->hack = $hack;
        return $this;
    }
}