<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adminpage_sidebar extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'name_path',
        'parent_id'
    ];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];
}
