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
    	$date = null;
    	$interval = '9months';
        $rate = $this->model->getPriborRate($date, $interval);
        $this->assertEquals(true, is_numeric($rate));
        $this->assertEquals(0.42, $rate);
    }

    public function testGetPriborRateForNonexistingInterval()
    {
    	$date = null;
    	$interval = 'abcde';
        $data = $this->model->getPriborRate($date, $interval);
        $this->assertNull($data);
    }
}
