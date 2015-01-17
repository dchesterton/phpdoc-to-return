<?php
namespace CSD\PhpdocToReturn;

class File
{
    /**
     * @var array
     */
    private $tokens;

    /**
     * @var string
     */
    private $fileName;

    private $functions;

    /**
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
        $this->tokens = token_get_all(file_get_contents($fileName));
    }

    /**
     * @return Func[] An array of functions
     */
    public function getFunctions()
    {
        if ($this->functions) {
            return $this->functions;
        }

        $currentClass = null;
        $currentNamespace = null;

        $classes = [];
        $scope = 0;
        $currentClassScope = -1;

        $functions = [];

        for ($i = 0, $count = count($this->tokens); $i < $count; $i++) {
            $token = $this->tokens[$i];

            if (is_array($token)) {
                switch ($token[0]) {
                    case T_NAMESPACE;

                        $namespace = '';

                        for ($j = $i + 2; $j < $count; $j++) {
                            if (';' === $this->tokens[$j] || '{' === $this->tokens[$j]) {
                                break;
                            }

                            if (is_array($this->tokens[$j]) && T_WHITESPACE === $this->tokens[$j][0]) {
                                continue;
                            }

                            $namespace .= $this->tokens[$j][1];
                        }

                        $currentNamespace = $namespace;

                        break;
                    case T_CLASS;
                    case T_INTERFACE;
                        $className = $this->tokens[$i + 2][1];

                        if ($currentNamespace) {
                            $className = $currentNamespace . '\\' . $className;
                        }

                        $classes[] = $className;

                        $currentClass = $className;
                        $currentClassScope = $scope;

                        break;
                    case T_FUNCTION:
                        // closure, ignore for now
                        if ('(' === $this->tokens[$i + 2]) {
                            break;
                        }

                        $functionName = $this->tokens[$i + 2][1];

                        if ($currentClass) {
                            $functions[$i] = new Func(new \ReflectionMethod($currentClass, $functionName), $this, $i);
                        } else {
                            $fqn = $currentNamespace . '\\' . $functionName;
                            $functions[$i] = new Func(new \ReflectionFunction($fqn), $this, $i);
                        }

                        break;

                    default:

                        //var_dump(token_name($token[2]));
                        break;
                }
            } else {
                if ($token == '{') {
                    ++$scope;
                }
                if ($token == '}') {
                    --$scope;
                }

                if ($scope == $currentClassScope) {
                    $currentClassScope = -1;
                    $currentClass = null;
                }
            }
        }

        $this->functions = $functions;

        return $this->functions;
    }

    public function write()
    {
        $out = '';

        foreach ($this->tokens as $token) {
            if (is_array($token)) {
                $out .= $token[1];
            } else {
                $out .= $token;
            }
        }

        file_put_contents(str_replace('/src/', '/src2/', $this->fileName), $out);
    }


    /**
     * @return array
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    public function insertToken($token, $pos)
    {
        array_splice($this->tokens, $pos, 0, $token);

        foreach ($this->functions as $tokenPos => $function) {
            if ($tokenPos > $pos) {
                unset($this->functions[$tokenPos]);
                $this->functions[$tokenPos + 1] = $function;
            }
        }
    }

    public function replaceToken($token, $pos)
    {
        $this->tokens[$pos] = $token;
    }

    /**
     * @param Func $func
     *
     * @return int
     */
    public function getStartTokenForFunction(Func $func)
    {
        return array_search($func, $this->functions);
    }
}