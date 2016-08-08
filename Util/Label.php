<?php

namespace Bubnov\TensorFlowBundle\Util;

class Label
{
    /**
     *
     * @var String
     */
    private $name;

    /**
     *
     * @var float
     */
    private $score;

    /**
     *
     * @param String $name
     * @param float $score
     */
    public function __construct($name, $score) {
        $this->name = $name;
        $this->score = $score;
    }

    /**
     *
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return String
     */
    public function __toString()
    {
        return $this->name . ' (' . $this->score . ')';
    }
}

