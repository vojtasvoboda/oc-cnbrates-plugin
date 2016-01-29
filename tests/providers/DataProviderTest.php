<?php

namespace VojtaSvoboda\CnbRates\Tests\Providers;

use App;
use File;
use PluginTestCase;

class DataProviderTest extends PluginTestCase
{
    private $model;

    private $cache_ident = 'vojtasvoboda_cnbrates';

    private $valid_url = 'https://www.cnb.cz/cs/financni_trhy/penezni_trh/pribor/denni.txt';

    public function setUp()
    {
        parent::setUp();
        $this->model = $this->getModel();
        File::deleteDirectory(temp_path($this->cache_ident), true);
    }

    private function getModel()
    {
        return App::make('VojtaSvoboda\CnbRates\Providers\CnbDataProvider');
    }

    public function testCacheFolderEmpty()
    {
        $all = File::allFiles(temp_path($this->cache_ident));
        $this->assertEmpty($all);
    }

    public function testGetEmptyData()
    {
        $this->setExpectedException('Exception', "Service URL can't be empty.");
        $this->model->getData($url = '', $date = null, $ident = null);
    }

    public function testGetDataWithBadUrl()
    {
        $url = 'http://www.this-url-not-exists.cz/';

        $this->setExpectedException('ErrorException');
        $this->model->getData($url, $date = null, $ident = null);
    }

    public function testGetDataWithBadDate()
    {
        $url = $this->valid_url;
        $date = 'abcdefg';

        $this->setExpectedException('Exception', 'DateTime::__construct(): Failed to parse time string (abcdefg) at position 0 (a): The timezone could not be found in the database');
        $this->model->getData($url, $date);
    }

    public function testGetData()
    {
        $url = $this->valid_url;
        $todayObj = new \DateTime();
        $today = $todayObj->format('d.m.Y');

        // at first time, data should be saved to file
        $data = $this->model->getData($url, $date = null, $ident = null);
        $this->assertNotEmpty($data);

        // check if data exists
        $filename = temp_path($this->cache_ident . '/undefined/' . $today . '.txt');
        $this->assertFileExists($filename);

        // get timestamp
        $mtime = File::lastModified($filename);
        $this->assertEquals(true, is_numeric($mtime));

        // try load data at second time (should be loaded from cache)
        $data2 = $this->model->getData($url, $date = null, $ident = null);
        $this->assertNotEmpty($data2);

        // data should be equal
        $this->assertEquals($data, $data2);

        // file should stay unmodified
        $this->assertEquals($mtime, File::lastModified($filename));
    }

    public function testGetDataWithDateAndIdent()
    {
        $url = $this->valid_url;

        // at first time, data should be saved to file
        $data = $this->model->getData($url, $date = '12.1.2016', $ident = 'test');
        $this->assertNotEmpty($data);

        // check if data exists
        $filename = temp_path($this->cache_ident . '/test/12.01.2016.txt');
        $this->assertFileExists($filename);
    }
}
