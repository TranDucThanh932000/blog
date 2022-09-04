<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'author_id', 'censor_id', 'date_publish'];

    public function users(){
        return $this->belongsToMany(User::class, 'users');
    }

}
