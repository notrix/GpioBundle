<?php

namespace Notrix\GpioBundle\Command;

use Notrix\GpioBundle\Service\PinManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Notrix\GpioBundle\Command\GpioInputCommand
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class GpioInputCommand extends Command
{
    /**
     * @var PinManagerInterface
     */
    protected $pinManager;

    /**
     * Class constructor
     *
     * @param PinManagerInterface $pinManager
     */
    public function __construct(PinManagerInterface $pinManager)
    {
        $this->pinManager = $pinManager;
        parent::__construct();
    }

    /**
     * Command configure
     *
     * @see \Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        $this
            ->setName('notrix:gpio:input')
            ->setDescription('Get gpio signal from pin')
            ->addOption('number', null, InputOption::VALUE_OPTIONAL, 'Numeric pin number')
            ->addOption('slug', null, InputOption::VALUE_OPTIONAL, 'String assigned to pin in configuration');
    }

    /**
     * Execute this command
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null|void
     * @throws \InvalidArgumentException
     * @see \Symfony\Component\Console\Command.Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasOption('number') && $number = $input->getOption('number')) {
            $pin = $this->pinManager->getPinByNumber($number);
        } elseif ($input->hasOption('slug') && $slug = $input->getOption('slug')) {
            $pin = $this->pinManager->getPinBySlug($slug);
        } else {
            throw new \InvalidArgumentException('Pin number or slug must be provided');
        }

        $result = $this->pinManager->getPinInput($pin);

        $output->writeln($result);
    }
}
