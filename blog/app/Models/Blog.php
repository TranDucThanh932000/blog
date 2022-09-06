<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['title', 'content', 'author_id', 'censor_id', 'date_publish'];
    protected $softDelete = ['deleted_at'];

    public function users(){
        return $this->belongsToMany(User::class, 'users');
    }

    public function blogmenus(){
        return $this->belongsToMany(Menu::class, 'blog_categories', 'blog_id', 'category_id');
    }

}
