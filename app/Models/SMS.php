<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
    use HasFactory;

        /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sms';

    protected $fillable = [
        'country_code',
        'number',
        'content',
        'type',
        'orige',
        'status_send'
    ];
}
