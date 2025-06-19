<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestMaster extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "test_masters";

    protected $fillable = [
        'name',
        'short_name'
    ];
}
