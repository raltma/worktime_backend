<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PieceClassification extends Model
{
    use HasFactory;

    protected $with = ['classification'];

    protected $hidden = ['piece_report_id', 'created_at', 'updated_at'];

    public function classification(){
        return $this
        ->belongsTo(Classification::class)
        ->withDefault();
    }
}
