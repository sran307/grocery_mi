<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermsCMSSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'terms_c_m_s_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}
