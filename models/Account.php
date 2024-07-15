<?php namespace GetByte\Whatsapp\Models;

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
}
