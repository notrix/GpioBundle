<?php

namespace Notrix\GpioBundle\Service;

use Notrix\GpioBundle\Entity\InputPin;
use Notrix\GpioBundle\Entity\OutputPin;
use Notrix\GpioBundle\Entity\Pin;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Notrix\GpioBundle\Service\SudoPinManager
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class SudoPinManager implements PinManagerInterface
{
    /**
     * @var string
     */
    protected $workingDirectory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var PinManagerInterface
     */
    protected $pinFinder;

    /**
     * Class constructor
     *
     * @param string              $workingDirectory
     * @param PinManagerInterface $pinFinder
     * @param string              $environment
     * @param LoggerInterface     $logger
     */
    public function __construct(
        $workingDirectory,
        PinManagerInterface $pinFinder,
        $environment,
        LoggerInterface $logger
    )
    {
        $this->workingDirectory = $workingDirectory;
        $this->pinFinder = $pinFinder;
        $this->environment = $environment;
        $this->logger = $logger;
    }

    /**
     * Gets pin by slug
     *
     * @param string $slug
     *
     * @return Pin
     *
     * @throws \Notrix\GpioBundle\Exception\PinNotFoundException
     */
    public function getPinBySlug($slug)
    {
        return $this->pinFinder->getPinBySlug($slug);
    }

    /**
     * Gets pin by number
     *
     * @param int $number
     *
     * @return Pin
     *
     * @throws \Notrix\GpioBundle\Exception\PinNotFoundException
     */
    public function getPinByNumber($number)
    {
        return $this->pinFinder->getPinByNumber($number);
    }

    /**
     * Sets up pins for work
     */
    public function setupPins()
    {
        $this->logger->debug(__METHOD__);
        $builder = $this->getCommandBuilder(
            'notrix:gpio:setup',
            array(
                '--env=' . $this->environment,
            )
        );

        $this->runProcess($builder->getProcess());
    }

    /**
     * Sets output pin value (true or false)
     *
     * @param OutputPin $pin
     * @param boolean   $value
     *
     * @throws \InvalidArgumentException
     */
    public function setPinOutput(OutputPin $pin, $value)
    {
        $this->logger->debug(__METHOD__, array($pin->getId(), $value));
        $builder = $this->getCommandBuilder(
            'notrix:gpio:output',
            array(
                '--number=' . $pin->getId(),
                '--status=' . $value,
                '--env=' . $this->environment,
            )
        );

        $this->runProcess($builder->getProcess());
    }

    /**
     * Gets pin input value (1 or 0)
     *
     * @param InputPin $pin
     *
     * @return int
     */
    public function getPinInput(InputPin $pin)
    {
        $this->logger->debug(__METHOD__, array($pin->getId()));
        $builder = $this->getCommandBuilder(
            'notrix:gpio:input',
            array(
                '--number=' . $pin->getId(),
                '--env=' . $this->environment,
            )
        );

        return $this->runProcess($builder->getProcess());
    }

    /**
     * Creates new command builder
     *
     * @param string $command
     * @param array  $arguments
     *
     * @return ProcessBuilder
     */
    protected function getCommandBuilder($command, array $arguments = array())
    {
        return ProcessBuilder::create()
            ->setPrefix('sudo')
            ->setArguments(array_merge(array('./console', $command), $arguments))
            ->setWorkingDirectory($this->workingDirectory);
    }

    /**
     * Runs process
     *
     * @param Process $process
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    protected function runProcess(Process $process)
    {
        $process->run();

        if (!$process->isSuccessful()) {
            $error = $process->getErrorOutput();
            if (strpos($error, 'sudo') === 0) {
                $this->logger->info('Set NOPASSWD permission for console in sudo configuration');
            }
            throw new \RuntimeException($error);
        }

        return $process->getOutput();
    }
}
