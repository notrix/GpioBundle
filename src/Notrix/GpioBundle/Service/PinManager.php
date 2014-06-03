<?php

namespace Notrix\GpioBundle\Service;

use Notrix\GpioBundle\Entity\Pin;
use Notrix\GpioBundle\Entity\InputPin;
use Notrix\GpioBundle\Entity\OutputPin;
use Notrix\GpioBundle\Exception\PinNotFoundException;
use PhpGpio\GpioInterface;
use Psr\Log\LoggerInterface;

/**
 * Notrix\GpioBundle\Service\PinManager
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class PinManager implements PinManagerInterface
{
    /**
     * @var GpioInterface
     */
    protected $gpio;

    /**
     * @var array
     */
    protected $slugMap;

    /**
     * @var InputPin[]
     */
    protected $inputPins;

    /**
     * @var OutputPin[]
     */
    protected $outputPins;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Class constructor
     *
     * @param GpioInterface   $gpio
     * @param array           $slugMap
     * @param InputPin[]      $inputPins
     * @param OutputPin[]     $outputPins
     * @param LoggerInterface $logger
     */
    public function __construct(
        GpioInterface $gpio,
        array $slugMap,
        array $inputPins,
        array $outputPins,
        LoggerInterface $logger
    )
    {
        $this->gpio = $gpio;
        $this->slugMap = $slugMap;
        $this->inputPins = $inputPins;
        $this->outputPins = $outputPins;
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
        $this->logger->debug(__METHOD__, array($slug));
        if (!isset($this->inputPins[$slug]) && !isset($this->outputPins[$slug])) {
            throw new PinNotFoundException(sprintf('Pin with slug %s not found', $slug));
        }

        return isset($this->inputPins[$slug]) ? $this->inputPins[$slug] : $this->outputPins[$slug];
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
        $this->logger->debug(__METHOD__, array($number));
        if (!isset($this->slugMap[$number])) {
            throw new PinNotFoundException(sprintf('Pin with number %d not found', $number));
        }

        return $this->getPinBySlug($this->slugMap[$number]);
    }

    /**
     * Sets up pins for work
     */
    public function setupPins()
    {
        $this->logger->debug(__METHOD__);

        $this->_setupPins(Pin::DIRECTION_IN, $this->inputPins);
        $this->_setupPins(Pin::DIRECTION_OUT, $this->outputPins);
    }

    /**
     * Sets up pins for work
     *
     * @param string $direction
     * @param Pin[]  $pins
     */
    protected function _setupPins($direction, array $pins)
    {
        foreach ($pins as $pin) {
            if (
                !$this->gpio->isExported($pin->getId()) ||
                $this->gpio->currentDirection($pin->getId()) != $direction
            ) {
                $this->logger->debug('Setting up pins', array(
                    'exported'  => $this->gpio->isExported($pin->getId()),
                    'direction' => $direction,
                ));
                $this->gpio->setup($pin->getId(), $direction);
            }
        }
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
        $this->gpio->output($pin->getId(), $value ? 1 : 0);
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

        return $this->gpio->input($pin->getId());
    }
}
