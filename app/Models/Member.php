<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $table = 'members';
    
    protected $fillable = [
        "name",
        "gender",
        "email",
        "phone_number",
        "address"
    ];

    public function user()
    {
        return $this->hasOne(User::class,'member_id');
    }
}
