<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PieceClassification extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $with = ['classification'];

    protected $hidden = ['piece_report_id', 'created_at', 'updated_at'];

    public function classification(){
        return $this
        ->belongsTo(Classification::class)
        ->withDefault();
    }
}
