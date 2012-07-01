<?php

class DeftTest extends PHPUnit_Framework_TestCase {

    public function testConstruction() {
        $fileMgr = new Deft\FileManager;
        $deft = new Deft($fileMgr);
        $this->assertInstanceOf('Deft', $deft);
    }

}
