<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BuildPcCategory extends Model
{
    protected $primaryKey = 'id';
    public $table = 'build_pc_category';
    protected $hidden = [
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo('App\CategoryManagementSettings','category_id','id');
    }
}
