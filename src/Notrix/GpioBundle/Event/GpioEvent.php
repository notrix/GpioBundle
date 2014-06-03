<?php

namespace Notrix\GpioBundle\Event;

use Notrix\GpioBundle\Entity\Pin;
use Symfony\Component\EventDispatcher\Event;

/**
 * Notrix\GpioBundle\Event\GpioEvent
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class GpioEvent extends Event
{
    /**
     * @var string
     */
    protected $eventName;

    /**
     * @var Pin
     */
    protected $pin;

    /**
     * @var bool
     */
    protected $status;

    /**
     * Class constructor
     *
     * @param string $eventName
     * @param Pin    $pin
     * @param bool   $status
     */
    public function __construct($eventName = null, Pin $pin = null, $status = null)
    {
        $this->eventName = $eventName;
        $this->pin = $pin;
        $this->status = (bool) $status;
    }

    /**
     * Setter of EventName
     *
     * @param string $eventName
     *
     * @return GpioEvent
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * Getter of EventName
     *
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * Setter of Pin
     *
     * @param \Notrix\GpioBundle\Entity\Pin $pin
     *
     * @return GpioEvent
     */
    public function setPin(Pin $pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Getter of Pin
     *
     * @return \Notrix\GpioBundle\Entity\Pin
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Setter of Status
     *
     * @param boolean $status
     *
     * @return GpioEvent
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Getter of Status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
}
