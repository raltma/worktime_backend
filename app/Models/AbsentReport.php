<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsentReport extends Model
{
    use HasFactory;

    protected $hidden = [ 'created_at', 'updated_at', 'user_id'];
    protected $with = ['user'];

    public function user(){
        return $this
        ->belongsTo(User::class)
        ->withDefault();
    }
}
