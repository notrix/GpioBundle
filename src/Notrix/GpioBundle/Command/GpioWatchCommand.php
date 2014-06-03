<?php

namespace Notrix\GpioBundle\Command;

use Notrix\GpioBundle\Service\PinWatcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Notrix\GpioBundle\Command\GpioWatchCommand
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class GpioWatchCommand extends Command
{
    /**
     * @var PinWatcher
     */
    protected $pinWatcher;

    /**
     * Class constructor
     *
     * @param PinWatcher $pinWatcher
     */
    public function __construct(PinWatcher $pinWatcher)
    {
        $this->pinWatcher = $pinWatcher;
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
            ->setName('notrix:gpio:watch')
            ->setDescription('Run gpio input pin watcher');
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
        $output->write('Waiting for status changes of pins: ');
        $output->writeln(implode(', ', $this->pinWatcher->getInputPins()));
        $this->pinWatcher->run();
    }
}
