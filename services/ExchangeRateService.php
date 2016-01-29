<?php

namespace VojtaSvoboda\CnbRates\Services;

class ExchangeRateService extends BaseService
{
    protected $baseUrl = 'https://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/denni_kurz.txt';

    protected $ident = 'exchange';

    /**
     * Get all exchange rates
     *
     * @param null $date Returns today rates by default
     *
     * @return array
     */
    public function getExchangeRates($date = null)
    {
        $rates = $this->getData($date, $keyIndex = 3);
        $r = [];

        foreach($rates as $key => $rate)
        {
            if (sizeof($rate) >= 4)
            {
                $rateAsNumber = round(floatval(strtr($rate[4], [',' => '.'])), 3);

                $r[$key] = [
                    'country' => $rate[0],
                    'currency' => $rate[1],
                    'base' => intval($rate[2]),
                    'symbol' => $rate[3],
                    'rate' => $rateAsNumber,
                ];
            }
        }

        return $r;
    }

    /**
     * Return exchange rate for specific currency and date
     *
     * @param string $currency
     * @param null $date Returns today rates by default
     *
     * @return float
     */
    public function getExchangeRate($currency = 'EUR', $date = null)
    {
        $data = $this->getExchangeRates($date);

        if (isset($data[$currency]['rate'])) {
            return $data[$currency]['rate'];
        }

        return null;
    }
}
