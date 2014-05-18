<?php

namespace AndyTruong\Common\TypedData\Plugin;

class ItemList extends Base
{

    /**
     * @var string
     */
    protected $element_type = NULL;

    public function setDefinition($definition)
    {
        // Find element type from parent type. E.g type.list<integer> => integer
        if (0 === strpos($definition['type'], 'list<')) {
            $this->element_type = substr($definition['type'], 5, -1);
        }

        return parent::setDefinition($definition);
    }

    public function isEmpty()
    {
        if (!is_null($this->input)) {
            return is_empty($this->input);
        }
    }

    public function validateInput(&$error = NULL)
    {
        if (!is_array($this->input)) {
            $error = 'Input must be an array.';
            return FALSE;
        }

        if (!is_null($this->element_type)) {
            $this->validateElementType($error);
            if (!empty($error)) {
                return FALSE;
            }
        }

        return parent::validateInput($error);
    }

    private function validateElementType(&$error = NULL)
    {
        $data = at_data(array('type' => $this->element_type));

        foreach ($this->input as $k => $input) {
            $data->setInput($input);
            if (!$data->validate()) {
                $error = "Element <strong>{$k}</strong> is not type of {$this->element_type}";
                return FALSE;
            }
        }

        return TRUE;
    }

}
