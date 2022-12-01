<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxManagement extends Model
{
    protected $primaryKey = 'id';
    public $table = 'tax_managements';
    protected $hidden = [
        'created_at','updated_at'
    ];

    public function MainCat()
    {
        return $this->belongsTo('App\CategoryManagementSettings','main_cat_name','id');
    }
}
