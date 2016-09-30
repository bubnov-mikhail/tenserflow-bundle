<?php

namespace Bubnov\TensorFlowBundle\Util;

class Label
{
    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var float
     */
    private $score;

    /**
     *
     * @param string $name
     * @param float $score
     */
    public function __construct($name, $score)
    {
        $this->name = $name;
        $this->score = $score;
    }

    /**
     *
     * @return string
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
     * @return string
     */
    public function __toString()
    {
        return $this->name . ' (' . $this->score . ')';
    }
}
