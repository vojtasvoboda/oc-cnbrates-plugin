<?php

namespace VojtaSvoboda\CnbRates\Models;

use October\Rain\Database\Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'vojtasvoboda_cnbrates_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

}