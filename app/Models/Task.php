<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "tasks";
    protected $fillable = [
        'user_id',
        'status_master_id',
        'title',
        'description',
        'deadline',
        'status'
    ];

}
