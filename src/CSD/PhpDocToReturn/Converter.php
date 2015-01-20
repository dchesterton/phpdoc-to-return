<?php
namespace CSD\PhpDocToReturn;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Converter
{
    /**
     * @param Func $function
     * @param bool $hack
     *
     * @return bool
     */
    private function shouldWriteReturnType(Func $function, $hack)
    {
        $name = $function->getReflection()->getName();

        if ($function->getReflection() instanceof \ReflectionMethod && in_array($name, ['__construct', '__destruct', '__clone'])) {
            return false;
        }

        $comment = $function->getReturnComment();

        if (!$comment) {
            return false;
        }

        return ($comment->getType() && $comment->getType()->getDeclaration($hack));
    }

    /**
     * @param File $file
     * @param bool $hack
     */
    public function convert(File $file, $hack)
    {
        foreach ($file->getFunctions() as $function) {
            $this->convertFunction($function, $hack);
        }
    }

    /**
     * @param Func $function
     * @param bool $hack
     */
    private function convertFunction(Func $function, $hack)
    {
        if (!$this->shouldWriteReturnType($function, $hack)) {
            return;
        }

        $returnComment = $function->getReturnComment();
        $returnType = $returnComment->getType();

        $declaration = ': ' . $returnType->getDeclaration($hack);

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
        if (!$returnComment->getComment() && $returnType->isDocCommentRedundant()) {
            // work backwards from start of function and try and find the doc comment
            for ($i = $startToken - 1; $i >= 0; $i--) {
                $token = $tokens[$i];

                if (is_array($token)) {
                    if (in_array($token[0], [T_WHITESPACE, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_ABSTRACT, T_FINAL, T_STATIC])) {
                        continue;
                    }

                    if ($token[0] == T_DOC_COMMENT) {
                        $token[1] = preg_replace('/[\s\*]*@return\s.*/im', '', $token[1]);

                        // remove empty doc comments
                        if (preg_match('#^[\s\*\/\\\\]*$#', $token[1])) {
                            $token[1] = '';

                            // remove any excess whitespace
                            $nextToken = $tokens[$i + 1];

                            if (is_array($nextToken) && $nextToken[0] == T_WHITESPACE) {
                                $nextToken[1] = '';
                                $file->replaceToken($nextToken, $i + 1);
                            }
                        }

                        $file->replaceToken($token, $i);
                    }

                    break;
                }
            }
        }
    }
}
