<?php

namespace VojtaSvoboda\CnbRates\Tests\Services;

use App;
use File;
use PluginTestCase;

class ExchangeRateServiceTest extends PluginTestCase
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
        $mock->setIdent('Exchange');

        return App::make('VojtaSvoboda\CnbRates\Services\ExchangeRateService', [$mock]);
    }

    public function testGetExchangeRates()
    {
        $data = $this->model->getExchangeRates();
        $this->assertEquals(true, is_array($data));
        $this->assertEquals(31, sizeof($data));
        $this->assertEquals(27.065, $data['EUR']['rate']);
    }

    public function testGetExchangeRateForNonexistingCurrency()
    {
    	$currency = 'ABC';
        $rate = $this->model->getExchangeRate($currency);
        $this->assertNull($rate);
    }

    public function testGetPriborRate()
    {
    	$currency = 'EUR';
        $data = $this->model->getExchangeRate($currency);
        $this->assertEquals(27.065, $data);
    }
}
