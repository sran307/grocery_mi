<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CMSPageManagement extends Model
{
    protected $primaryKey = 'id';
    public $table = 'c_m_s_page_managements';
    protected $hidden = [
        'created_at','updated_at'
    ];
}
