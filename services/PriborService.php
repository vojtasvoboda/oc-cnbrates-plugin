<?php

namespace VojtaSvoboda\CnbRates\Services;

class PriborService extends BaseService
{
    protected $baseUrl = 'https://www.cnb.cz/cs/financni_trhy/penezni_trh/pribor/denni.txt';

    protected $ident = 'pribor';

    /**
     * Returns all PRIBOR rates for specific date
     *
     * @param null $date Returns today rates by default
     *
     * @return array
     */
    public function getPriborRates($date = null)
    {
        $data = $this->getData($date);
        $intervalKeys = $this->getIntervalKeys();
        $returnRates = [];

        foreach($data as $key => $rate) {
            if (isset($intervalKeys[$key])) {
                $returnRates[$intervalKeys[$key]] = $this->priceStringToFloat($rate[2], 2);
            }
        }

        return $returnRates;
    }

    /**
     * Returns PRIBOR rates for specific date and interval
     *
     * @param null $date Returns today rates by default
     * @param string $interval Year interval by default
     *
     * @return float
     */
    public function getPriborRate($date = null, $interval = 'year')
    {
        $data = $this->getPriborRates($date);

        if (isset($data[$interval])) {
            return $data[$interval];
        }

        return null;
    }

    /**
     * Get pairing keys between plugins API and ČNB format
     *
     * @return array
     */
    private function getIntervalKeys()
    {
        return [
            '1 rok' => 'year',
            '9 měsíců' => '9months',
            '6 měsíců' => '6months',
            '3 měsíce' => '3months',
            '2 měsíce' => '2months',
            '1 měsíc' => 'month',
            '14 dní' => '2weeks',
            '7 dní' => 'week',
            '1 den' => 'day',
        ];
    }
}
