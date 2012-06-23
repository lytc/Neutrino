<?php

namespace neutrino;

class Regex
{
    /**
     * @var string
     */
    protected $_pattern;

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->_pattern = $pattern;
    }

    /**
     * @param string $subject
     * @param int $flags
     * @param int $offset
     * @return array|null
     */
    public function match($subject, $flags = 0, $offset = 0)
    {
        preg_match($this->_pattern, $subject, $matches, $flags, $offset);
        return $matches;
    }

    /**
     * @param string $subject
     * @param int $flags
     * @param int $offset
     * @return array|null
     */
    public function matchAll($subject, $flags = PREG_PATTERN_ORDER, $offset = 0)
    {
        preg_match_all($this->_pattern, $subject, $matches, $flags, $offset);
        return $matches;
    }
}