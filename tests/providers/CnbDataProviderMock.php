<?php

namespace VojtaSvoboda\CnbRates\Tests\Providers;

use File;
use VojtaSvoboda\CnbRates\Providers\CnbDataProvider;

class CnbDataProviderMock extends CnbDataProvider
{
    private $ident = 'Pribor';

    public function getData($url, $date = null, $ident = null)
    {
        return File::get(__DIR__ . '/CnbDataProviderMock' . $this->ident . '.txt');
    }

    public function setIdent($ident)
    {
        $this->ident = $ident;
    }
}