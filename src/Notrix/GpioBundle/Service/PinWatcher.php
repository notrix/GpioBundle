<?php

namespace Notrix\GpioBundle\Service;

use Notrix\GpioBundle\Entity\InputPin;
use Notrix\GpioBundle\Exception\PinNotFoundException;
use Psr\Log\LoggerInterface;
use React\EventLoop\Factory as LoopFactory;

/**
 * Notrix\GpioBundle\Service\PinWatcher
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class PinWatcher
{
    /**
     * @var LoopFactory
     */
    protected $loopFactory;

    /**
     * @var InputPin[]
     */
    protected $inputPins = array();

    /**
     * @var PinManagerInterface
     */
    protected $pinManager;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var float
     */
    protected $interval;

    /**
     * @var array
     */
    private $pinStatusList = array();

    /**
     * Class constructor
     *
     * @param LoopFactory         $loopFactory
     * @param PinManagerInterface $pinManager
     * @param EventDispatcher     $eventDispatcher
     * @param LoggerInterface     $logger
     * @param float               $interval
     */
    public function __construct(
        LoopFactory $loopFactory,
        PinManagerInterface $pinManager,
        EventDispatcher $eventDispatcher,
        $interval,
        LoggerInterface $logger = null
    ) {
        $this->loopFactory = $loopFactory;
        $this->pinManager = $pinManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
        $this->interval = $interval;
    }

    /**
     * Adds input pin to watcher
     *
     * @param InputPin $pin
     *
     * @return PinWatcher
     */
    public function addInputPin(InputPin $pin)
    {
        $this->inputPins[$pin->getId()] = $pin;

        return $this;
    }

    /**
     * Setter on input pins
     *
     * @param array $inputPins
     *
     * @return PinWatcher
     */
    public function setInputPins(array $inputPins)
    {
        foreach ($inputPins as $pin) {
            $this->addInputPin($pin);
        }

        return $this;
    }

    /**
     * Reads pins and throws events on change
     *
     * @return PinWatcher
     */
    protected function readPins()
    {
        foreach ($this->inputPins as $pin) {
            $status = $this->pinManager->getPinInput($pin);
            if ($this->getPinStatus($pin) === null) {
                $this->setPinStatus($pin, $status);
            } elseif ($this->getPinStatus($pin) != $status) {
                $this->eventDispatcher->dispatch($pin, $status);
                $this->setPinStatus($pin, $status);
                if ($this->logger) {
                    $this->logger->info(
                        'Detected pin status change',
                        array($pin->getId(), $pin->getSlug(), $status)
                    );
                }
            }
        }

        return $this;
    }

    /**
     * Setter of pin status
     *
     * @param InputPin $pin
     * @param int      $status
     *
     * @return PinWatcher
     */
    protected function setPinStatus(InputPin $pin, $status)
    {
        $this->pinStatusList[$pin->getId()] = $status;

        return $this;
    }

    /**
     * Getter of pin status
     *
     * @param InputPin $pin
     *
     * @return int|null
     */
    protected function getPinStatus(InputPin $pin)
    {
        return isset($this->pinStatusList[$pin->getId()]) ? $this->pinStatusList[$pin->getId()] : null;
    }

    /**
     * Watches input pins in given interval of time
     *
     * @throws \Notrix\GpioBundle\Exception\PinNotFoundException
     */
    public function run()
    {
        if (empty($this->inputPins)) {
            throw new PinNotFoundException('No input pins configured to watch');
        }
        $loop = $this->loopFactory->create();
        $loop->addPeriodicTimer($this->interval, function () {
            $this->readPins();
        });
        $loop->run();
    }

    /**
     * Getter of InputPins
     *
     * @return \Notrix\GpioBundle\Entity\InputPin[]
     */
    public function getInputPins()
    {
        return $this->inputPins;
    }
}
