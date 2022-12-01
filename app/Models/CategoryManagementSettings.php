<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryManagementSettings extends Model
{
    protected $primaryKey = 'id';
    public $table = 'category_management_settings';
    protected $hidden = [
        'created_at','updated_at'
    ];
}
