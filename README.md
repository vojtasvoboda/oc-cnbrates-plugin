# ČNB Rates plugin for OctoberCMS

[![Build Status](https://travis-ci.org/vojtasvoboda/oc-cnbrates-plugin.svg?branch=master)](https://travis-ci.org/vojtasvoboda/oc-cnbrates-plugin)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/vojtasvoboda/oc-cnbrates-plugin/blob/master/LICENSE)

ČNB Rates plugin provide financial data from ČNB (Czech National Bank).

Implemented ČNB services: Exchange rates, PRIBOR rates.

Features:
- automatically daily update
- fires event after update, so integration is very easy
- prepared for implementating other ČNB services
- covered by unit tests

Required plugins: none.

## Installation

- install plugin VojtaSvoboda.CnbRates from your Backend -> Settings -> System -> Updates -> Install plugins
- select which services you want to use at Settings -> System -> Misc -> ČNB Rates

## Events

The best and easiest way how to use this plugin is by events.

This plugin provide two events. When you set *Scheduled updates* correctly (see below), 
this events will be fired each day automatically and always comes with fresh rates data.

- **vojtasvoboda.cnbrates.exchange.updated** Fired when exchange rates are updated
- **vojtasvoboda.cnbrates.pribor.updated** Fired when PRIBOR rates are updated

Using event in your Plugin.php:

```
public function boot()
{
    // ČNB rates update listener
    Event::listen('vojtasvoboda.cnbrates.exchange.updated', function($rates)
    {
        // update my plugin with fresh data
        $this->updateMyProducts($rates);
    });
}
```

All methods are fired after calling update method e.g. *updateTodayExchangeRates()* 
manually or by scheduler.

## Scheduled updates

For scheduled updates to operate correctly, you should add the following Cron entry 
to your server. Editing the crontab is commonly performed with the command crontab -e.

`* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`

Be sure to replace /path/to/artisan with the absolute path to the artisan file in 
the root directory of October.

Thats all, rates will be updated every day and related events will be fired.

## Exchange rates service

Data are taken from [official daily exchange rate list](https://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/denni_kurz.jsp).

### Get all exchange rates

```
$cnb = App::make('cnb');
// rates for today
$rates = $cnb->getExchangeRates();
// for specific date
$rates = $cnb->getExchangeRates('12.1.2016');
```

### Get exchange rate only for EUR currency

```
$cnb = App::make('cnb');
// rates for today
$rates = $cnb->getExchangeRate();
// for specific date
$rates = $cnb->getExchangeRate('12.1.2016');
```

### Callable service used for CRON call

Downloads daily exchange rates, saves it to cache and fires 
`vojtasvoboda.cnbrates.exchange.updated` event. This method is prepared for 
using by CRON (see Setting CRON section).

```
$cnb = App::make('cnb');
$cnb->updateTodayExchangeRates();
```

## PRIBOR rates service

Data are taken from [official daily PRIBOR rate list](https://www.cnb.cz/cs/financni_trhy/penezni_trh/pribor/denni.jsp).

More about [PRIBOR](https://cs.wikipedia.org/wiki/Prague_InterBank_Offered_Rate).

PRIBOR is changed daily and is calculated for these intervals: year, 9 months, 6 months, 3 months, 2 months, month, 2 weeks, week and for one day.

### Get all PRIBOR rates

```
$cnb = App::make('cnb');
// rates for today
$rates = $cnb->getPriborRates();
// for specific date
$rates = $cnb->getPriborRates('12.1.2016');
```

### Get PRIBOR rate only for concrete interval

```
$cnb = App::make('cnb');
// today yearly PRIBOR rate
$rates = $cnb->getPriborRate($date = null, $interval = 'year');
// or shortcuts
$rates = $cnb->getPriborRateForYear('12.1.2016');
$rates = $cnb->getPriborRateFor9Months('12.1.2016');
$rates = $cnb->getPriborRateFor6Months('12.1.2016');
$rates = $cnb->getPriborRateFor3Months('12.1.2016');
$rates = $cnb->getPriborRateFor2Months('12.1.2016');
$rates = $cnb->getPriborRateForMonth('12.1.2016');
$rates = $cnb->getPriborRateFor2Weeks('12.1.2016');
$rates = $cnb->getPriborRateForWeek('12.1.2016');
$rates = $cnb->getPriborRateForDay('12.1.2016');
```

### Callable service used for CRON call

Download daily exchange rates, save it to cache and fires 
`vojtasvoboda.cnbrates.exchange.updated` event. This method is prepared for 
using by CRON.

```
$cnb = App::make('cnb');
$cnb->updateTodayExchangeRates();
```

## Testing

Run `phpunit` command in plugin directory. All test must pass.

## Future plans

**Feel free to send pullrequest!**

- implement other ČNB services

## License

ČNB Rates plugin is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT) same as OctoberCMS platform.

## Contributing

Please send Pull Request to master branch.
