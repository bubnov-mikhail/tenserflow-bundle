<?php

namespace Bubnov\TensorFlowBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RecognizeCombineDictFileCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('bubnov_tensorflow:combine_dict')
            ->addOption('tmpdict', null, InputOption::VALUE_OPTIONAL, 'Full path to temp dictionary file', '/tmp/tensorFlowBundle.tmp.dict')
            ->addOption('dict', null, InputOption::VALUE_OPTIONAL, 'Full path to dictionary file to create', '/tmp/tensorFlowBundle.dict')
            ->setDescription("Add labels to the temporary dictionary file\n")
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tmpdict = $input->getOption('tmpdict');
        $dict = $input->getOption('dict');
        $output->writeln('<info>Combining ' . $tmpdict . ' to ' . $dict . '</info>');

        try {
            if (!file_exists($tmpdict) || !is_readable($tmpdict)) {
                throw new \Exception($tmpdict . ' is not readable. Check if file exists and has read permissions');
            }

            $dictionary = [];
            $inputData = file_get_contents($tmpdict);
            foreach (explode("\n", $inputData) as $label) {
                if (!isset($dictionary[$label])) {
                    $dictionary[$label] = 0;
                }

                $dictionary[$label]++;
            }

            asort($dictionary, SORT_NUMERIC);
            $dictionary = array_reverse($dictionary);

            $outputData = [];
            foreach ($dictionary as $label => $count) {
                $outputData[] = $label . ' ' . $count;
            }
            file_put_contents($dict, implode("\n", $outputData), FILE_APPEND);
            
            $output->writeln("<info>Done\n</info>");
        } catch (\Exception $e) {
            $output->writeln('<error>Something went wrong</error>');
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}
