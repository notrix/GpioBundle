<?php

namespace Notrix\GpioBundle\Service;

use Notrix\GpioBundle\Entity\InputPin;
use Notrix\GpioBundle\Entity\OutputPin;
use Notrix\GpioBundle\Entity\Pin;

/**
 * Notrix\GpioBundle\Service\PinManagerInterface
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
interface PinManagerInterface
{
    /**
     * Gets pin by slug
     *
     * @param string $slug
     *
     * @return Pin
     *
     * @throws \Notrix\GpioBundle\Exception\PinNotFoundException
     */
    public function getPinBySlug($slug);

    /**
     * Gets pin by number
     *
     * @param int $number
     *
     * @return Pin
     *
     * @throws \Notrix\GpioBundle\Exception\PinNotFoundException
     */
    public function getPinByNumber($number);

    /**
     * Sets up pins for work
     */
    public function setupPins();

    /**
     * Sets output pin value (true or false)
     *
     * @param OutputPin $pin
     * @param boolean   $value
     *
     * @throws \InvalidArgumentException
     */
    public function setPinOutput(OutputPin $pin, $value);

    /**
     * Gets pin input value (1 or 0)
     *
     * @param InputPin $pin
     *
     * @return int
     */
    public function getPinInput(InputPin $pin);
}
