<?php

namespace AndyTruong\Common\TypedData;

use AndyTruong\Common\EventAware;
use AndyTruong\Common\TypedData\Plugin\PluginInterface;

/**
 * Manager for typed-data feature.
 *
 * @event at.typed_data.plugin.load
 */
class Manager extends EventAware
{

    protected $em_name = 'at.typed_data';

    protected static $plugins = array(
        'type.any' => 'AndyTruong\Common\TypedData\Plugin\Any',
        'type.boolean' => 'AndyTruong\Common\TypedData\Plugin\Boolean',
        'type.constant' => 'AndyTruong\Common\TypedData\Plugin\Constant',
        'type.function' => 'AndyTruong\Common\TypedData\Plugin\Fn',
        'type.integer' => 'AndyTruong\Common\TypedData\Plugin\Integer',
        'type.list' => 'AndyTruong\Common\TypedData\Plugin\ItemList',
        'type.closure' => 'AndyTruong\Common\TypedData\Plugin\Kallable',
        'type.mapping' => 'AndyTruong\Common\TypedData\Plugin\Mapping',
        'type.string' => 'AndyTruong\Common\TypedData\Plugin\String',
    );

    public function registerPlugin($id, $class_name)
    {
        if (isset(self::$plugins[$id])) {
            throw new \Exception('Typed-data plugin is already registered: ' . strip_tags($id));
        }

        $self::$plugins[$id] = $class_name;

        return true;
    }

    protected function loadPlugin($id)
    {
        if (!isset(self::$plugins[$id])) {
            $this->getEventManager()->trigger('at.typed_data.plugin.load', $this, array('id' => $id));
        }

        if (isset(self::$plugins[$id]) && class_exists(self::$plugins[$id])) {
            return new self::$plugins[$id];
        }

        throw new \Exception('Unknow typed-data plugin: ' . strip_tags($id));
    }

    /**
     * Get plugin object from typed-data definition.
     *
     * @param array $definition
     * @return PluginInterface
     */
    protected function findPluginFromDefinition(&$definition)
    {
        if (0 === strpos($definition['type'], 'type.')) {
            $id = $definition['type'];
        }
        else {
            $id = 'type.' . $definition['type'];
        }

        // Special type: list<element_type>
        if (strpos($id, 'type.list<') === 0) {
            $id = 'type.list';
        }

        return $this->loadPlugin($id);
    }

    /**
     * Wrapper method to get typed-data plugin.
     *
     * @param array $definition
     * @param type $input
     * @return PluginInterface
     * @throws \Exception
     */
    public function getPlugin($definition, $input)
    {
        if (!is_array($definition)) {
            throw new \Exception('Definition must be an array.');
        }

        if (!isset($definition['type'])) {
            throw new \Exception('Missing type key');
        }

        $plugin = $this->findPluginFromDefinition($definition);
        $plugin->setDefinition($definition);
        $plugin->setInput($input);
        return $plugin;
    }

}
