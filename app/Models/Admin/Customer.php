<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers'; // Use the correct table name
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
