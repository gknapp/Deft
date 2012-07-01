<?php

class DeftTest extends PHPUnit_Framework_TestCase {

    public function testConstruction() {
        $deft = new Deft;
        $this->assertInstanceOf('Deft', $deft);
    }

}
