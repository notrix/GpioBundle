<?php

namespace Notrix\GpioBundle\Entity;

/**
 * Notrix\GpioBundle\Entity\Pin
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
abstract class Pin
{
    const DIRECTION_IN = 'in';
    const DIRECTION_OUT = 'out';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $slug;

    /**
     * Class constructor
     *
     * @param int    $id
     * @param string $slug
     */
    public function __construct($id = null, $slug = null)
    {
        $this->id = $id;
        $this->slug = $slug;
    }

    /**
     * Setter of Id
     *
     * @param int $id
     *
     * @return Pin
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter of Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter of Slug
     *
     * @param string $slug
     *
     * @return Pin
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Getter of Slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * To string method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->slug ? $this->slug . " ({$this->id})" : $this->id;
    }
}
