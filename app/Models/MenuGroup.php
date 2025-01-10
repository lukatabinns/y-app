<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuGroup extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'dishes_count', 'selectable_dishes_count', 'groups'];

    public function setMenu()
    {
        return $this->belongsTo(Menu::class);
    }
}
