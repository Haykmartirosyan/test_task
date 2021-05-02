<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    /**
     * @var string
     */
    protected $table = 'data';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'value'
    ];
}
