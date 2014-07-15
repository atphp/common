<?php

namespace AndyTruong\Common\Fixtures;

use AndyTruong\Common\EventAware;

class EventAwareClass extends EventAware
{

    public $counter = 0;
    public $logs = array();

    public function increase($msg = '')
    {
        $this->counter++;
        if (!empty($msg)) {
            $this->logs[] = $msg;
        }
    }

    public function nothing()
    {
        $this->dispatch(__FUNCTION__);
    }

    public function noParams()
    {
        $this->trigger(__FUNCTION__, $this);
    }

    public function full($baz, $bat = null)
    {
        $this->trigger(__FUNCTION__, $this, compact('baz', 'bat'));
    }

    public function stopPropagation()
    {
        $this->trigger(__FUNCTION__, $this);
    }

    /**
     * @return ResponseCollection
     */
    public function collectValues()
    {
        $this->trigger(__FUNCTION__, $this);
    }

}
