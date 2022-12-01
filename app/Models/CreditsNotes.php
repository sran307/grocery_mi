<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditsNotes extends Model
{
    protected $primaryKey = 'id';
    public $table = 'credits_notes';
    protected $hidden = [
        'updated_at'
    ];

    public function GRV()
    {
        return $this->belongsTo('App\GrvOrders','grv_id','id');
    }
}
