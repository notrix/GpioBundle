<?php

namespace Notrix\GpioBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NotrixGpioExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $inPins = $this->loadInputPins($config['in']);
        $outPins = $this->loadOutputPins($config['out']);

        $slugMap = array();
        foreach ($inPins + $outPins as $pin) {
            $slugMap[$pin->getArgument(0)] = $pin->getArgument(1);
        }

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('gpio_lib.xml');
        $loader->load('services.xml');

        if ($config['development']) {
            $container->setParameter('notrix_gpio.lib_gpio.class', 'PhpGpio\GpioDevelop');
        }

        if ($config['watcher_interval']) {
            $container->setParameter('notrix_gpio.pin_watcher.interval', $config['watcher_interval']);
        }

        $pinWatcher = $container->getDefinition('notrix_gpio.pin_watcher');
        $pinWatcher->addMethodCall('setInputPins', array($inPins));

        $plainPinManager = $container->getDefinition('notrix_gpio.plain_pin_manager');
        $plainPinManager->replaceArgument(1, $slugMap);
        $plainPinManager->replaceArgument(2, $inPins);
        $plainPinManager->replaceArgument(3, $outPins);

        if ($config['sudo']) {
            $alias = new Alias('notrix_gpio.sudo_pin_manager', true);
        } else {
            $alias = new Alias('notrix_gpio.plain_pin_manager', true);
        }
        $container->setAlias('notrix_gpio.pin_manager', $alias);
    }

    /**
     * Loads input pins from configuration
     *
     * @param array $configuration
     *
     * @return Definition[]
     */
    protected function loadInputPins(array $configuration)
    {
        $inPins = array();
        foreach ($configuration as $pinNr => $options) {
            $slug = !empty($options['slug']) ? $options['slug'] : $pinNr;
            $pin = new Definition('Notrix\GpioBundle\Entity\InputPin', array($pinNr, $slug));

            if (!empty($options['event'])) {
                $on = $off = current($options['event']);
                if (count($options['event']) == 2) {
                    if ($options['event'] === array_values($options['event'])) {
                        list($on, $off) = $options['event'];
                    } else {
                        $on = $options['event']['on'];
                        $off = $options['event']['off'];
                    }
                }
                $pin->addMethodCall('setActiveEvent', array($on));
                $pin->addMethodCall('setInactiveEvent', array($off));
            }
            $inPins[$slug] = $pin;
        }
        return $inPins;
    }

    /**
     * Loads output pins from configuration
     *
     * @param array $configuration
     *
     * @return Definition[]
     */
    protected function loadOutputPins(array $configuration)
    {
        $outPins = array();
        foreach ($configuration as $pinNr => $options) {
            $slug = !empty($options['slug']) ? $options['slug'] : $pinNr;
            $pin = new Definition('Notrix\GpioBundle\Entity\OutputPin', array($pinNr, $slug));
            $outPins[$slug] = $pin;
        }
        return $outPins;
    }
}
