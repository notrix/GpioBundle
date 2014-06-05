<?php

namespace Notrix\GpioBundle\Command;

use Notrix\GpioBundle\Service\PinManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Notrix\GpioBundle\Command\GpioSetupCommand
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class GpioSetupCommand extends Command
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
            ->setName('notrix:gpio:setup')
            ->setDescription('Setup gpio pins to input and output modes');
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
        $this->pinManager->setupPins();
        $output->writeln('Setup has finished');
    }
}
