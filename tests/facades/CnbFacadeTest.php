<?php

namespace VojtaSvoboda\CnbRates\Tests\Facades;

use App;
use Event;
use File;
use PluginTestCase;

class CnbFacadeTest extends PluginTestCase
{
    private $model;

    private $cacheIdent = 'vojtasvoboda_cnbrates';

    private $events = [];

    public function setUp()
    {
        parent::setUp();
        $this->model = $this->getModel();
        $this->eventCatcherInit();
        File::deleteDirectory(temp_path($this->cacheIdent), true);
    }

    private function eventCatcherInit()
    {
        Event::listen('vojtasvoboda.cnbrates.pribor.updated', function() {
            $this->events[] = 'vojtasvoboda.cnbrates.pribor.updated';
        });
        Event::listen('vojtasvoboda.cnbrates.exchange.updated', function() {
            $this->events[] = 'vojtasvoboda.cnbrates.exchange.updated';
        });
    }

    private function getModel()
    {
        return App::make('VojtaSvoboda\CnbRates\Facades\CnbFacade');
    }

    //--- Exchange Service Tests

    public function testGetExchangeRates()
    {
        $rates = $this->model->getExchangeRates();
        $this->assertEquals(true, is_array($rates), 'Rates are not array.');
        $this->assertGreaterThanOrEqual(1, sizeof($rates));
    }

    public function testGetExchangeRateForSpecificDate()
    {
        $rates = $this->model->getExchangeRates('10.1.2016');
        $this->assertEquals(true, is_array($rates), 'Rates are not array.');
        $this->assertGreaterThanOrEqual(1, sizeof($rates));
    }

    public function testGetExchangeRate()
    {
    	$currency = 'EUR';
        $rate = $this->model->getExchangeRate($currency);
        $this->assertNotEmpty($rate);
        $this->assertGreaterThan(0.000, $rate);
    }

    public function testGetNonexistExchangeRate()
    {
    	$currency = 'ABC';
        $rate = $this->model->getExchangeRate($currency);
        $this->assertNull($rate);
    }

    public function testUpdateTodayExchangeRates()
    {
        $this->model->updateTodayExchangeRates();
        $this->assertContains('vojtasvoboda.cnbrates.exchange.updated', $this->events);
    }

    //--- PRIBOR Service Tests

    public function testGetPribor()
    {
        $rates = $this->model->getPriborRates();
        $this->assertEquals(true, is_array($rates), 'Rates are not array.');
    }

    public function testGetPriborForSpecificDate()
    {
        $rates = $this->model->getPriborRates('10.1.2016');
        $this->assertEquals(true, is_array($rates), 'Rates are not array.');
    }

    public function testGetPriborForSpecificInterval()
    {
    	$interval = '9months';
        $rate = $this->model->getPriborRate($date = null, $interval);
        $rate9 = $this->model->getPriborRateFor9Months($date = null);
        $this->assertEquals($rate, $rate9);
    }

    public function testUpdateTodayPriborRates()
    {
        $this->model->updateTodayPriborRates();
        $this->assertContains('vojtasvoboda.cnbrates.pribor.updated', $this->events);
    }
}
