<?php

namespace Bubnov\TensorFlowBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class RecognizeCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('bubnov_tensorflow:recognize')
            ->addArgument('image', InputArgument::REQUIRED, 'Full image path')
            ->setDescription("Recognize image by tensorflow\n")
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $image = $input->getArgument('image');
        $output->writeln('<info>Recognizing image ' . $image . '</info>');

        try {
            /* @var $recognizer \Bubnov\TensorFlowBundle\Service\TenserFlowRecognizer */
            $recognizer = $this->getContainer()->get('tenserflow.recognizer');
            $result = $recognizer->recognize($image);
            $output->writeln($result->__toString());
            
            $output->writeln("<info>Done\n</info>");
        } catch (\Exception $e) {
            $output->writeln('<error>Something went wrong</error>');
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}
