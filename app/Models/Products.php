<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $primaryKey = 'id';
    public $table = 'products';
    protected $hidden = [
        'updated_at'
    ];

    public function ProductBrand()
    {
        return $this->belongsTo('App\Brands','brand','id');
    }

    public function MainCat()
    {
        return $this->belongsTo('App\CategoryManagementSettings','main_cat_name','id');
    }

    public function SubCat()
    {
        return $this->belongsTo('App\SubCategoryManagementSettings','sub_cat_name','sub_cat_id');
    }

    public function SubSubCat()
    {
        return $this->belongsTo('App\SubSubCategoryManagementSettings','sub_sub_cat_name','sub_sub_cat_id');
    }

    public function Measurement()
    {
        return $this->belongsTo('App\MeasurementUnits','measurement_unit','id');
    }

    public function Creatier()
    {
        return $this->belongsTo('App\User','created_user','id');
    }

    public function Modifier()
    {
        return $this->belongsTo('App\User','modified_user','id');
    }

    public function Store()
    {
        return $this->belongsTo('App\Store','store','id');
    }

    public function MStore()
    {
        return $this->belongsTo('App\Store','created_user','merchant');
    }

    public function Attributes()
    {
        return $this->hasMany('App\ProductsAttributes','product_id','id');
    }

    public function PImages()
    {
        return $this->hasMany('App\ProductsImages','product_id','id');
    }
}
