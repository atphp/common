<?php

namespace AndyTruong\Common\TestCases\Functions;

class AtIdTest extends \PHPUnit_Framework_TestCase
{

    public function testOk()
    {
        $this->assertEquals('DateTimeZone', get_class(at_id(new \DateTime())->getTimezone()));
    }

}
