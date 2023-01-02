<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AbsentReport extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $hidden = [ 'created_at', 'updated_at', 'user_id'];
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
