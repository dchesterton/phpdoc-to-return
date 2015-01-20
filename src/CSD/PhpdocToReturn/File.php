<?php
namespace CSD\PhpdocToReturn;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 */
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

    /**
     * @var Func[]
     */
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
     *
     */
    public function parse()
    {
        if (null !== $this->functions) {
            return;
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
                    case T_DOLLAR_OPEN_CURLY_BRACES:
                    case T_CURLY_OPEN:
                        ++$scope;

                        break;
                    case T_NAMESPACE;

                        $namespace = '';

                        for ($j = $i + 2; $j < $count; $j++) {
                            if (';' === $this->tokens[$j] || '{' === $this->tokens[$j]) {
                                break;
                            }

                            if (T_WHITESPACE !== $this->tokens[$j][0]) {
                                $namespace .= $this->tokens[$j][1];
                            }
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

                        $functionName = null;

                        for ($j = $i; $j < $count; $j++) {
                            if (is_array($this->tokens[$j]) && $this->tokens[$j][0] == T_STRING) {
                                $functionName = $this->tokens[$j][1];
                                break;
                            }
                        }

                        if ($currentClass) {
                            $functions[$i] = new Func(new \ReflectionMethod($currentClass, $functionName), $this);
                        } else {
                            $fqn = $currentNamespace . '\\' . $functionName;
                            $functions[$i] = new Func(new \ReflectionFunction($fqn), $this);
                        }

                        break;
                }
            } else {
                if ($token == '{') {
                    ++$scope;
                } else if ($token == '}') {
                    --$scope;
                }

                if ($scope == $currentClassScope) {
                    $currentClassScope = -1;
                    $currentClass = null;
                }
            }
        }

        $this->functions = $functions;
    }

    /**
     * @return Func[] An array of functions
     */
    public function getFunctions()
    {
        $this->parse();
        return $this->functions;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        $out = '';

        foreach ($this->tokens as $token) {
            if (is_array($token)) {
                $out .= $token[1];
            } else {
                $out .= $token;
            }
        }

        return $out;
    }

    /**
     * @return array
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @param mixed $token
     * @param int   $pos
     */
    public function insertToken($token, $pos)
    {
        $this->parse();

        array_splice($this->tokens, $pos, 0, $token);

        foreach ($this->functions as $tokenPos => $function) {
            if ($tokenPos > $pos) {
                unset($this->functions[$tokenPos]);
                $this->functions[$tokenPos + 1] = $function;
            }
        }
    }

    /**
     * @param mixed $token
     * @param int   $pos
     */
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
        $this->parse();

        return array_search($func, $this->functions);
    }
}