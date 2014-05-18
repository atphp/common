<?php

namespace AndyTruong\Common\TypedData\Plugin;

abstract class MappingBase extends Base
{

    protected $allow_extra_properties = TRUE;

    public function setDefinition($def)
    {
        $this->definition = $def;

        if (isset($def['allow_extra_properties'])) {
            $this->allow_extra_properties = $def['allow_extra_properties'];
        }

        return $this;
    }

    public function getValue()
    {
        if ($this->validate()) {
            $value = array();

            foreach ($this->input as $k => $v) {
                $value[$k] = $this->getItemValue($k, $v);
            }

            return $value;
        }
    }

    private function getItemValue($k, $v)
    {
        $return = $v;

        if (isset($this->definition['mapping'][$k]['type'])) {
            $def = array('type' => $this->definition['mapping'][$k]['type']);
            $data = at_data($def, $v);
            $return = $data->getValue();
        }

        return $return;
    }

}
