<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PieceClassification;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class HourReport extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'date_selected',
        'shift',
        'hours',
        'user_id',
        'overtime',
        'overtime_hours'
    ];
    
    protected $with = ['user', 'confirmer'];
    public function user(){
        return $this
        ->belongsTo(User::class)
        ->withDefault();
    }
    public function confirmer(){
        return $this
        ->belongsTo(User::class,"confirmer_id","id")
        ->withDefault();
    }
}
