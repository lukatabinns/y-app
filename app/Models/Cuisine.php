<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuisine extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    protected $hidden = ['pivot'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_cuisine', 'cuisine_id', 'menu_id');
    }
}
