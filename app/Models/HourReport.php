<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PieceClassification;
use App\Models\User;

class HourReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_selected',
        'shift',
        'hours',
        'user_id',
        'overtime',
        'overtime_hours'
    ];
    
    public function user(){
        return $this
        ->belongsTo(User::class)
        ->withDefault();
    }
}
