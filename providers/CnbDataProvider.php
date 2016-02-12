<?php

namespace VojtaSvoboda\CnbRates\Providers;

/**
 * Provide data from ČNB and save it to cache
 *
 * @package VojtaSvoboda\CnbRates\Providers
 */
class CnbDataProvider
{
    private $cachePathPrefix = 'vojtasvoboda_cnbrates';

    private $undefinedFolderName = 'undefined';

    /**
     * Get source data
     *
     * @param $url
     * @param null $date Concrete date specifier
     * @param null $ident Service idend for caching
     *
     * @return string
     *
     * @throws
     */
    public function getData($url, $date = null, $ident = null)
    {
        if (!$url) {
            throw new \Exception("Service URL can't be empty.");
        }

        $dateObj = new \DateTime($date);
        $date = $dateObj->format('d.m.Y');

        if (!$this->isFileExists($date, $ident)) {
            $this->saveDataToFile($this->loadDataFromUrl($url), $date, $ident);
        }

        return $this->loadDataFromFile($date, $ident);
    }

    /**
     * Load data from URL
     *
     * @param string $url
     *
     * @return string
     */
    private function loadDataFromUrl($url)
    {
        $url .= (is_numeric(substr($url, -1)) ? '&r=' : '?r=') . time();
        $data = file_get_contents($url);

        return $data;
    }

    /**
     * Load data from file
     *
     * @param $date
     * @param $ident
     *
     * @return string
     */
    private function loadDataFromFile($date, $ident)
    {
        return file_get_contents($this->getFilePath($date, $ident));
    }

    /**
     * Save loaded data to file
     *
     * @param $data
     * @param $date
     * @param $ident
     *
     * @return int
     */
    private function saveDataToFile($data, $date, $ident)
    {
        $this->checkCacheFolder($ident);

        return file_put_contents($this->getFilePath($date, $ident), $data);
    }

    /**
     * If file exists
     *
     * @param $date
     * @param $ident
     *
     * @return bool
     */
    private function isFileExists($date, $ident)
    {
        return file_exists($this->getFilePath($date, $ident));
    }

    /**
     * Get file path by date and service ident
     *
     * @param $date
     * @param null $ident
     *
     * @return string
     */
    private function getFilePath($date, $ident = null)
    {
        if (!$ident) {
            $ident = $this->undefinedFolderName;
        }

        return temp_path($this->cachePathPrefix . '/' . $ident . '/' . $date . '.txt');
    }

    /**
     * Create cache folder if not exists
     *
     * @param $ident
     */
    private function checkCacheFolder($ident)
    {
        $cacheFolder = temp_path($this->cachePathPrefix . '/');
        if (!file_exists($cacheFolder)) {
            mkdir($cacheFolder);
        }

        if (!$ident) {
            $ident = $this->undefinedFolderName;
        }

        $identFolder = temp_path($this->cachePathPrefix . '/' . $ident . '/');
        if (!file_exists($identFolder)) {
            mkdir($identFolder);
        }
    }
}