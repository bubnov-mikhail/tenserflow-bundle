<?php

namespace Bubnov\TensorFlowBundle\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Process\Process;
use Bubnov\TensorFlowBundle\Util\Label;
use Bubnov\TensorFlowBundle\Util\RecognizerResult;

class TenserFlowRecognizer
{
    const COMMAND_TEMPLATE = '%s --graph="%s" --labels="%s" --image="%s"';

    const LINE_PREG = '/(.+?) \(([\d\.]+)\)/i';

    /**
     *
     * @var string path to label_image binary
     */
    private $binary;
    
    /**
     *
     * @var String path to graph.pb
     */
    private $graph;

    /**
     *
     * @var String path to labels.txt
     */
    private $labels;

    /**
     *
     * @param String $binDir
     */
    public function __construct($binary, $graph, $labels) {
        $this
            ->testFilePath($binary, true)
            ->testFilePath($graph)
            ->testFilePath($labels)
        ;
        
        $this->binary = $binary;
        $this->graph = $graph;
        $this->labels = $labels;
    }

    /**
     *
     * @param File | string $file
     * @return \Bubnov\TensorFlowBundle\Service\RecognizerResult
     * @throws \RuntimeException
     */
    public function recognize($file)
    {
        $filePath = $this->getFilePath($file);
        $this->testFilePath($filePath);

        $process = $this->getProcess($file);
        $process->run();

        return $this->generateResult($process->getOutput());
    }

    /**
     *
     * @param File | string $file
     * @return String
     * @throws \RuntimeException
     */
    private function getFilePath($file) {
        if ($file instanceof File) {
            return $file->getPath().'/'.$file->getBasename();
        } elseif (is_string($file)) {
            return $file;
        }

        throw new \RuntimeException(__METHOD__ . ': accepts attribute $file to be Symfony\Component\HttpFoundation\File\File or string with filepath');
    }

    /**
     *
     * @param String $filePath
     * @param Boolean $mustBeExecutable
     * @return \Bubnov\TensorFlowBundle\Service\TenserFlowRecognizer
     * @throws \RuntimeException
     */
    private function testFilePath($filePath, $mustBeExecutable = false) {
        if (!file_exists($filePath) || !is_file($filePath)) {
            throw new \RuntimeException(__METHOD__ . ': there is no file in ' . $filePath);
        }

        if (!is_readable($filePath)) {
            throw new \RuntimeException(__METHOD__ . ': ' . $filePath . ' is not readable');
        }

        if ($mustBeExecutable && !is_executable($filePath)) {
            throw new \RuntimeException(__METHOD__ . ': ' . $filePath . ' is not executable');
        }

        return $this;
    }

    /**
     *
     * @param String $file
     * @return \Symfony\Component\Process\Process
     */
    private function getProcess($file) {
        $cmd = sprintf(
            self::COMMAND_TEMPLATE,
            $this->binary,
            $this->graph,
            $this->labels,
            $file
        );

        $process = new Process($cmd);
        $process->setTimeout(null);

        return $process;
    }

    /**
     *
     * @param String $response
     * @return \Bubnov\TensorFlowBundle\Service\RecognizerResult
     */
    private function generateResult($response) {
        $result = new RecognizerResult();
        foreach (explode("\n", $response) as $line) {
            if (!$label = $this->generateLabel($line)) {
                continue;
            }

            $result->addLabel($label);
        }

        return $result;
    }

    /**
     *
     * @param String $line
     * @return Label
     */
    private function generateLabel($line) {
        if (empty($line)) {
            return null;
        }

        preg_match(self::LINE_PREG, $line, $match);
        if (3 !== count($match) || !$match[1] || !$match[2]) {
            return null;
        }
        
        return new Label($match[1], floatval($match[2]));
    }
}