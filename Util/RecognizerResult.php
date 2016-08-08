<?php

namespace Bubnov\TensorFlowBundle\Util;

use Bubnov\TensorFlowBundle\Util\Label;

class RecognizerResult
{
    /**
     *
     * @var Array of Label
     */
    private $labels = [];

    /**
     *
     * @param \Bubnov\TensorFlowBundle\Util\Label $label
     * @return \Bubnov\TensorFlowBundle\Util\RecognizerResult
     */
    public function addLabel(Label $label)
    {
        $this->labels[] = $label;

        return $this;
    }

    /**
     *
     * @return \Bubnov\TensorFlowBundle\Util\Label | null
     */
    public function getTopLabel()
    {
        return (count($this->labels))
            ? $this->labels[0]
            : null
        ;
    }

    /**
     *
     * @return Array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     *
     * @param float $scoreThreshold
     * @return Array
     */
    public function getLabelsScored($scoreThreshold)
    {
        $result = [];
        foreach ($this->labels as $label) {
            /* @var $label \Bubnov\TensorFlowBundle\Util\Label */
            if ($label->getScore() >= $scoreThreshold) {
                $result[] = $label;
            }
        }

        return $result;
    }

    /**
     * @return String
     */
    public function __toString()
    {
        return implode($this->labels, "\n");
    }
}

