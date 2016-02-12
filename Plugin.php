<?php namespace VojtaSvoboda\CnbRates;

use System\Classes\PluginBase;
use VojtaSvoboda\CnbRates\Models\Settings;

/**
 * CnbRates Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'vojtasvoboda.cnbrates::lang.plugin.name',
            'description' => 'vojtasvoboda.cnbrates::lang.plugin.description',
            'author' => 'Vojta Svoboda',
            'icon' => 'icon-line-chart'
        ];
    }

    public function boot()
    {
        $this->app->bind('cnb', 'VojtaSvoboda\CnbRates\Facades\CnbFacade');
    }

    public function registerSettings()
    {
        return [
            'config' => [
                'label' => 'vojtasvoboda.cnbrates::lang.settings.label',
                'icon' => 'icon-line-chart',
                'description' => 'vojtasvoboda.cnbrates::lang.settings.description',
                'class' => 'VojtaSvoboda\CnbRates\Models\Settings',
                'order' => 500
            ]
        ];
    }

    public function registerSchedule($schedule)
    {
        // Exchange service daily update when allowed by Settings
        $schedule->call(function() {
            $cnb = $this->app->make('cnb');
            $cnb->updateTodayExchangeRates();

        })->daily()->when(function() {
            return !!Settings::get('exchange', true);
        });

        // PRIBOR service daily update when allowed by Settings
        $schedule->call(function() {
            $cnb = $this->app->make('cnb');
            $cnb->updateTodayPriborRates();

        })->daily()->when(function() {
            return !!Settings::get('pribor', true);
        });
    }

}