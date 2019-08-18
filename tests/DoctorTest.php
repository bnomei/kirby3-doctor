<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Bnomei\Doctor;
use PHPUnit\Framework\TestCase;

class DoctorTest extends TestCase
{
    public function testLog()
    {
        $this->assertFalse(Bnomei\Doctor::log());
    }

    public function testFindComposerLockFile()
    {
        $this->assertNotNull(Bnomei\Doctor::findComposerLockFile());
    }

    public function testCli()
    {
        $this->assertIsInt(Bnomei\Doctor::cli());
    }

    public function testCheck()
    {
        $this->assertIsArray(Bnomei\Doctor::check());
        $this->assertIsArray(Bnomei\Doctor::check(true));
    }

    public function testReadCheckDefaults()
    {
        $this->assertIsArray(Bnomei\Doctor::readCheckDefaults());
        $this->assertCount(0, Bnomei\Doctor::readCheckDefaults('invalidpath'));
    }
}
