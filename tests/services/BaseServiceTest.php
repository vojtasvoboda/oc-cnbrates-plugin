<?php

namespace VojtaSvoboda\CnbRates\Tests\Services;

use App;
use File;
use PluginTestCase;

class BaseServiceTest extends PluginTestCase
{
    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model = $this->getModel();
    }

    private function getModel()
    {
        return App::make('VojtaSvoboda\CnbRates\Services\BaseService');
    }

    private function getModelWithMockedSource()
    {
        $mock = App::make('VojtaSvoboda\CnbRates\Tests\Providers\CnbDataProviderMock');
        $mock->setIdent('Pribor');

        return App::make('VojtaSvoboda\CnbRates\Services\BaseService', [$mock]);
    }

    public function testGetDataSource()
    {
        $model = $this->model;
        $this->setExpectedException('Exception', "Service URL can't be empty.");
        $model->getDataSource();
    }

    public function testGetIdent()
    {
        $this->assertNull($this->model->getIdent());
    }

    public function testGetSourceUrl()
    {
        $dataSource = $this->model->getSourceUrl();
        $this->assertEquals($dataSource, $this->model->getBaseUrl());
        $this->assertNotContains('vojtasvoboda_cnbrates', $dataSource);
    }

    public function testGetSourceUrlWithDate()
    {
        $date = '21.1.2016';
        $dataSource = $this->model->getSourceUrl($date);
        $this->assertEquals($dataSource, $this->model->getBaseUrl() . '?datum=21.01.2016');
        $this->assertNotContains('vojtasvoboda_cnbrates', $dataSource);
    }

    public function testGetMockedData()
    {
        $model = $this->getModelWithMockedSource();
        $data = $model->getData();
        $this->assertEquals($data['9 měsíců'][2], "0,42");
    }

    public function testPriceStringToFloat()
    {
        $model = $this->model;
        $this->assertEquals(1.23, $model->priceStringToFloat("1,234", 2));
        $this->assertEquals(1.234, $model->priceStringToFloat("1,234", 3));
    }
}
