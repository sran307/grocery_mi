<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BuildPcSubCategory extends Model
{
    protected $primaryKey = 'id';
    public $table = 'build_pc_sub_category';
    protected $hidden = [
        'updated_at'
    ];

    public function Users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}
