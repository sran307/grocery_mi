<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AboutUsCMSSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'about_us_c_m_s_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}
