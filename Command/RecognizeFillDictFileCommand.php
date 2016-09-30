<?php

namespace Bubnov\TensorFlowBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RecognizeFillDictFileCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('bubnov_tensorflow:fill_dict')
            ->addArgument('image', InputArgument::REQUIRED, 'Full image path')
            ->addOption('tmpdict', null, InputOption::VALUE_OPTIONAL, 'Full path to temp dictionary file', '/tmp/tensorFlowBundle.tmp.dict')
            ->setDescription("Add labels to the temporary dictionary file\n")
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $image = $input->getArgument('image');
        $tmpdict = $input->getOption('tmpdict');
        $output->writeln('<info>Recognizing image ' . $image . '</info>');

        try {
            /* @var $recognizer \Bubnov\TensorFlowBundle\Service\TenserFlowRecognizer */
            $recognizer = $this->getContainer()->get('tenserflow.recognizer');

            /* @var $recognizer \Bubnov\TensorFlowBundle\Util\RecognizerResult */
            $result = $recognizer->recognize($image);
            foreach ($result->getLabels() as $label) {
                /* @var $label \Bubnov\TensorFlowBundle\Util\Label */
                file_put_contents($tmpdict, $label->getName() . "\n", FILE_APPEND);
            }
            
            $output->writeln("<info>Done\n</info>");
        } catch (\Exception $e) {
            $output->writeln('<error>Something went wrong</error>');
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}
