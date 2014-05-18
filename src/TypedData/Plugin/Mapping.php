<?php

namespace AndyTruong\Common\TypedData\Plugin;

/**
 * at_data($schema, $input)->validate($error);
 *
 * Example schema, check `at_base/config/schema/route.yml`
 */
class Mapping extends MappingBase
{
    public function validate(&$error = NULL)
    {
        return $this->validateDefinition($error) && $this->validateInput($error);
    }

    protected function validateDefinition(&$error)
    {
        if (!parent::validateDefinition($error)) {
            return false;
        }

        if (empty($this->definition['skip validate mapping'])) {
            if (!$this->validateDefMapping($error)) {
                return false;
            }
        }

        return true;
    }

    protected function validateDefMapping(&$error)
    {
        if (!isset($this->definition['mapping'])) {
            $error = 'Wrong schema: Missing mapping property';
            return FALSE;
        }

        if (!is_array($this->definition['mapping'])) {
            $error = 'Mapping property of data definition must be an array.';
            return FALSE;
        }

        $element_schema = array(
            'type' => 'mapping',
            'skip validate mapping' => TRUE,
            'mapping' => array(
                'type' => array('type' => 'string', 'required' => TRUE),
                'required' => array('type' => 'boolean'),
                'label' => array('type' => 'string'),
                'description' => array('type' => 'string'),
            )
        );

        foreach ($this->definition['mapping'] as $k => $e) {
            if (!at_data($element_schema, $e)->validate($error)) {
                $error = "Wrong schema: {$error}";
                return FALSE;
            }
        }

        return TRUE;
    }

    protected function validateInput(&$error)
    {
        if (!is_array($this->input)) {
            $error = 'Input must be an array.';
            return FALSE;
        }

        return $this->validateRequiredProperties($error) && $this->validateAllowingExtraProperties($error) && $this->validateElementType($error) && $this->validateRequireOne($error) && parent::validateInput($error)
        ;
    }

    protected function validateRequiredProperties(&$error)
    {
        foreach ($this->definition['mapping'] as $k => $item_def) {
            if (!empty($item_def['required']) && !isset($this->input[$k])) {
                $error = "Property {$k} is required.";
                return FALSE;
            }
        }
        return TRUE;
    }

    private function validateAllowingExtraProperties(&$error)
    {
        if (!$this->allow_extra_properties) {
            foreach (array_keys($this->input) as $k) {
                if (!isset($this->definition['mapping'][$k])) {
                    $error = 'Unexpected key found: ' . $k . '.';
                    return FALSE;
                }
            }
        }

        return TRUE;
    }

    protected function validateElementType(&$error)
    {
        foreach ($this->input as $k => $v) {
            if (!empty($this->definition['mapping'][$k])) {
                if (!at_data($this->definition['mapping'][$k], $v)->validate($error)) {
                    $error = "Invalid property `{$k}`: {$error}";
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    /**
     * Supported require_one_of:
     *
     *  Example 1: ['key 1', 'key 2']
     *  Example 1: [['key 1.1', 'key 1.2'], 'key 2']
     */
    protected function validateRequireOne(&$error)
    {
        if (empty($this->definition['require_one_of'])) {
            return TRUE;
        }

        foreach ($this->definition['require_one_of'] as $keys) {
            if ($this->validateRequireOneKeys($keys)) {
                return TRUE;
            }
        }

        $error = 'Missing one of  required keys: ' . print_r($this->definition['require_one_of'], TRUE);

        return FALSE;
    }

    private function validateRequireOneKeys($keys)
    {
        is_string($keys) && $keys = array($keys);

        $provided = TRUE;

        foreach ($keys as $k) {
            if (!isset($this->input[$k])) {
                $provided = FALSE;
                break;
            }
        }

        return $provided;
    }

}
