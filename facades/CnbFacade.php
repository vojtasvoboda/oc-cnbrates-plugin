<?php

namespace VojtaSvoboda\CnbRates\Facades;

use Event;
use VojtaSvoboda\CnbRates\Services\ExchangeRateService;
use VojtaSvoboda\CnbRates\Services\PriborService;

class CnbFacade
{
    private $exchangeRateService;

    private $priborService;

    public function __construct(ExchangeRateService $ers, PriborService $ps)
    {
        $this->exchangeRateService = $ers;
        $this->priborService = $ps;
    }


    //--- Exchange Services


    public function getExchangeRates($date = null)
    {
        return $this->exchangeRateService->getExchangeRates($date);
    }

    public function updateTodayExchangeRates()
    {
        $rates = $this->getExchangeRates();

        Event::fire('vojtasvoboda.cnbrates.exchange.updated', [$rates]);

        return $rates;
    }

    public function getExchangeRate($currency = 'CZK', $date = null)
    {
        return $this->exchangeRateService->getExchangeRate($currency, $date);
    }


    //--- PRIBOR Services


    public function getPriborRates($date = null)
    {
        return $this->priborService->getPriborRates($date);
    }

    public function updateTodayPriborRates()
    {
        $rates = $this->getPriborRates();

        Event::fire('vojtasvoboda.cnbrates.pribor.updated', [$rates]);

        return $rates;
    }

    public function getPriborRate($date = null, $interval = 'year')
    {
        return $this->priborService->getPriborRate($date, $interval);
    }


    //--- PRIBOR shortcuts


    public function getPriborRateForYear($date = null)
    {
        return $this->getPriborRate($date, 'year');
    }

    public function getPriborRateFor9Months($date = null)
    {
        return $this->getPriborRate($date, '9months');
    }

    public function getPriborRateFor6Months($date = null)
    {
        return $this->getPriborRate($date, '6months');
    }

    public function getPriborRateFor3Months($date = null)
    {
        return $this->getPriborRate($date, '3months');
    }

    public function getPriborRateFor2Months($date = null)
    {
        return $this->getPriborRate($date, '2months');
    }

    public function getPriborRateForMonth($date = null)
    {
        return $this->getPriborRate($date, 'month');
    }

    public function getPriborRateFor2Weeks($date = null)
    {
        return $this->getPriborRate($date, '2weeks');
    }

    public function getPriborRateForWeek($date = null)
    {
        return $this->getPriborRate($date, 'week');
    }

    public function getPriborRateForDay($date = null)
    {
        return $this->getPriborRate($date, 'day');
    }
}