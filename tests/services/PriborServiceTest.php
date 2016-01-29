<?php

namespace VojtaSvoboda\CnbRates\Tests\Services;

use App;
use File;
use PluginTestCase;

class PriborServiceTest extends PluginTestCase
{
    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model = $this->getModel();
    }

    private function getModel()
    {
        $mock = App::make('VojtaSvoboda\CnbRates\Tests\Providers\CnbDataProviderMock');
        $mock->setIdent('Pribor');

        return App::make('VojtaSvoboda\CnbRates\Services\PriborService', [$mock]);
    }

    public function testGetPriborRates()
    {
        $data = $this->model->getPriborRates();
        $this->assertEquals(true, is_array($data));
        $this->assertEquals(8, sizeof($data));
        $this->assertEquals(0.42, $data['9months']);
    }

    public function testGetPriborRate()
    {
        $rate = $this->model->getPriborRate($date = null, '9months');
        $this->assertEquals(true, is_numeric($rate));
        $this->assertEquals(0.42, $rate);
    }

    public function testGetPriborRateForNonexistingInterval()
    {
        $data = $this->model->getPriborRate($date = null, 'abcde');
        $this->assertNull($data);
    }
}
