<?php

namespace Notrix\GpioBundle\Entity;

use Notrix\GpioBundle\GpioEvents;

/**
 * Notrix\GpioBundle\Entity\InputPin
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class InputPin extends Pin
{
    /**
     * @var string
     */
    protected $activeEvent = GpioEvents::EVENT_DEFAULT;

    /**
     * @var string
     */
    protected $inactiveEvent = GpioEvents::EVENT_DEFAULT;

    /**
     * Setter of ActiveEvent
     *
     * @param string $activeEvent
     *
     * @return OutputPin
     */
    public function setActiveEvent($activeEvent)
    {
        $this->activeEvent = $activeEvent;

        return $this;
    }

    /**
     * Getter of ActiveEvent
     *
     * @return string
     */
    public function getActiveEvent()
    {
        return $this->activeEvent;
    }

    /**
     * Setter of InactiveEvent
     *
     * @param string $inactiveEvent
     *
     * @return OutputPin
     */
    public function setInactiveEvent($inactiveEvent)
    {
        $this->inactiveEvent = $inactiveEvent;

        return $this;
    }

    /**
     * Getter of InactiveEvent
     *
     * @return string
     */
    public function getInactiveEvent()
    {
        return $this->inactiveEvent;
    }
}
