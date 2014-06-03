<?php

namespace Notrix\GpioBundle\Service;

use Notrix\GpioBundle\Entity\InputPin;
use Notrix\GpioBundle\Event\GpioEvent;
use Notrix\GpioBundle\GpioEvents;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Notrix\GpioBundle\Service\EventDispatcher
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class EventDispatcher
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Class constructor
     *
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     */
    public function __construct(EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }

    /**
     * Creates and dispatches event for gpio pin status change
     *
     * @param InputPin $pin
     * @param int      $status
     *
     * @return GpioEvent
     */
    public function dispatch(InputPin $pin, $status)
    {
        $this->logger->debug(__METHOD__, array($pin->getSlug(), $status));
        $pinEvent = $status ? $pin->getActiveEvent() : $pin->getInactiveEvent();

        $event = new GpioEvent($pinEvent, $pin, $status);
        $this->dispatcher->dispatch($pinEvent, $event);
        if ($pinEvent != GpioEvents::EVENT_DEFAULT) {
            $this->dispatcher->dispatch(GpioEvents::EVENT_DEFAULT, $event);
        }

        return $event;
    }
}
