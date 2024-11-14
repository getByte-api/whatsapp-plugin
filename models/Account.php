<?php namespace GetByte\Whatsapp\Models;

use GetByte\Whatsapp\Classes\WhatsAppEvolution;
use GetByte\Whatsapp\Classes\WhatsAppEvolutionGetByte;
use GetByte\Whatsapp\Classes\WhatsAppWpp;
use GetByte\Whatsapp\Classes\WhatsAppWppGetByte;
use Model;
use October\Rain\Database\Traits\Validation;

/**
 * Model
 */
class Account extends Model
{
    use Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'getbyte_whatsapp_accounts';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'       => 'required',
        'secret_key' => 'required|unique',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    protected $fillable = [
        'secret_key',
        'name',
        'status',
        'connected_at',
    ];

    protected $dates = ['connected_at'];

    public function getApiCheckStatusUrlAttribute()
    {
        return url('whatsapp/check-status/' . $this->id);
    }

    public function getClassProvider(): string
    {
        if ($this->whatsapp_type == 'whatsapp-wpp') {
            return WhatsAppWpp::class;
        } else if ($this->whatsapp_type == 'whatsapp-evolution') {
            return WhatsAppEvolution::class;
        } else if ($this->whatsapp_type == 'wpp-getbyte') {
            return WhatsAppWppGetByte::class;
        } else if ($this->whatsapp_type == 'evolution-getbyte') {
            return WhatsAppEvolutionGetByte::class;
        }
    }
}
