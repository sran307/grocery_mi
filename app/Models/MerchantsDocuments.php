<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MerchantsDocuments extends Model
{
    protected $primaryKey = 'id';
    public $table = 'merchants_documents';
    protected $hidden = [
        'created_at','updated_at'
    ];
}
