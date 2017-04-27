<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'tests';
    protected $fillable = array(
        'first_name',
        'last_name',
        'bdate',
        'sex',
        'country');
}