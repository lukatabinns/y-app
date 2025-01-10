<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'image', 'thumbnail', 'is_vegan', 'is_vegetarian',
        'price_per_person', 'min_spend', 'is_seated', 'is_standing', 'is_canape',
        'is_mixed_dietary', 'is_meal_prep', 'is_halal', 'is_kosher', 'number_of_orders', 'available'
    ];

    public function cuisines()
    {
        return $this->belongsToMany(Cuisine::class, 'menu_cuisine', 'menu_id', 'cuisine_id');
    }

    public function groups()
    {
        return $this->hasMany(MenuGroup::class);
    }
}
