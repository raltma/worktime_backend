<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PieceReport extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $hidden = [ 'created_at', 'updated_at', 'user_id'];
    protected $with = ['classifications', 'user'];
    public function classifications(){
        return $this->hasMany(PieceClassification::class,"piece_report_id");
    }

    public function user(){
        return $this
        ->belongsTo(User::class)
        ->withDefault();
    }
}
