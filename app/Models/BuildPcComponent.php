<?php

namespace App;
use App\CategoryManagementSettings;
use Illuminate\Database\Eloquent\Model;

class BuildPcComponent extends Model
{
    protected $primaryKey = 'id';
    public $table = 'build_pc_components';
    protected $hidden = [
        'updated_at'
    ];

    public function category($id)
    {
        $as=CategoryManagementSettings::find($id);
        return $as->cat_name;
    }
    
}
