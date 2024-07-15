<?php namespace GetByte\Whatsapp\Models;

use Model;
use October\Rain\Database\Traits\Validation;
use System\Behaviors\SettingsModel;

class Settings extends Model
{
    use Validation;

    public $implement = [SettingsModel::class];

    public $settingsCode = 'getbyte_whatsapp_settings';
    public $settingsFields = 'fields.yaml';

    public $rules = [
    ];
}
