<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SubSubCategoryManagementSettings extends Model
{
    protected $primaryKey = 'sub_sub_cat_id';
    public $table = 'sub_sub_category_management_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}
