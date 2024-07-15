<?php namespace GetByte\Whatsapp\Models;

use Carbon\Carbon;
use Model;
use October\Rain\Database\Traits\Validation;

/**
 * Model
 */
class MessageLog extends Model
{
    use Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'getbyte_whatsapp_logs';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    protected $fillable = [
        'to_phone_number',
        'account_id',
        'message_type',
        'content',
        'error',
        'sent_at',
    ];

    protected $dates = ['sent_at'];

    protected $jsonable = ['content', 'error'];

    public $timestamps = false;

    public $belongsTo = [
        'account' => Account::class
    ];

    protected function beforeCreate()
    {
        $this->sent_at = Carbon::now();
    }
}
