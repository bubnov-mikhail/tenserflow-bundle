<?php

namespace Bubnov\TensorFlowBundle\Util;

use Bubnov\TensorFlowBundle\Util\Label;
use Bubnov\TensorFlowBundle\Util\RecognizerResult;

class Dictionary
{
    private $words = [];
    /**
     *
     * @param String $words
     * @return \Bubnov\TensorFlowBundle\Util\Dictionary
     */
    public function add($word)
    {
        $this->words[] = $words;

        return $this;
    }

    /**
     *
     * @param \Bubnov\TensorFlowBundle\Util\RecognizerResult $result
     * @param float $scoreThreshold
     * @return boolean
     */
    public function match(RecognizerResult $result, $scoreThreshold = 0.0)
    {
        foreach ($result->getLabelsScored($scoreThreshold) as $label) {
            /* @var $label \Bubnov\TensorFlowBundle\Util\Label */
            if ($this->hasLabel($label)) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @param \Bubnov\TensorFlowBundle\Util\Label $label
     * @return boolean
     */
    public function hasLabel(Label $label)
    {
        foreach ($this->words as $word) {
            if ($label->getName() === $word) {
                return true;
            }
        }

        return false;
    }
}

