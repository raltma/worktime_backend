<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at', 'bs_id'];

    public function users()
    {
        return $this->hasMany(User::class, "bs_department_id", "bs_id");
    }

    public function adminUsers(){
        return $this
        ->belongsToMany(USER::class,"admin_departments");
    }
}
