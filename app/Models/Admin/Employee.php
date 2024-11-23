<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees'; // Use the correct table name
    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone_number',
        'profile_image',
        'joining_date',
        'gender',
        'state',
        'file',
    ];
}
