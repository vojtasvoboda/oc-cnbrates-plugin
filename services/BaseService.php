<?php

namespace VojtaSvoboda\CnbRates\Services;

use VojtaSvoboda\CnbRates\Providers\CnbDataProvider;

class BaseService
{
    protected $dataProvider;

    protected $baseUrl;

    protected $ident;

    public function __construct(CnbDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * Return service data
     *
     * @param $date
     *
     * @return array
     */
    public function getDataSource($date = null)
    {
        return $this->dataProvider->getData($this->getSourceUrl($date), $date, $this->ident);
    }

    /**
     * Get data as array
     *
     * @param null $date
     * @param int $keyIndex
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getData($date = null, $keyIndex = 0)
    {
        $data = $this->dataProvider->getData($this->getSourceUrl($date), $date, $this->ident);

        return $this->transformSourceToArray($data, $keyIndex);
    }

    /**
     * Get service identifier
     *
     * @return mixed
     */
    public function getIdent()
    {
        return $this->ident;
    }

    /**
     * Get service source URL
     *
     * @param $date
     *
     * @return mixed
     */
    public function getSourceUrl($date = null)
    {
        if ($date) {
            $dateObj = new \DateTime($date);
            $date = '?datum=' . $dateObj->format('d.m.Y');
        }

        return $this->baseUrl . $date;
    }

    /**
     * Returns service base URL
     *
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Convert price string to float number
     *
     * @param $string
     * @param $rounding
     *
     * @return float
     */
    public function priceStringToFloat($string, $rounding = 2)
    {
        $stringWithDots = strtr($string, [',' => '.']);

        return round(floatval($stringWithDots), $rounding);
    }

    /**
     * Transform data source to array
     *
     * @param $source
     * @param int $keyIndex
     *
     * @return array
     */
    private function transformSourceToArray($source, $keyIndex = 0)
    {
        $return = [];
        $lines = preg_split('/\r\n|\n|\r/', trim($source));

        foreach($lines as $key => $line)
        {
            // file headers
            if ($key < 3) {
                continue;
            }

            // file lines
            $data = explode('|', $line);
            $return[$data[$keyIndex]] = $data;
        }

        return $return;
    }
}