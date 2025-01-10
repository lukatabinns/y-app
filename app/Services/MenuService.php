<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Cuisine;

class MenuService
{
    /**
     * Get filtered and paginated set menus.
     *
     * @param string|null $cuisineSlug
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getMenus(?string $cuisineSlug, int $perPage = 10)
    {
        // Base query to fetch menus with necessary data
        $query = Menu::select('id','name', 'description', 'price_per_person', 'min_spend', 'number_of_orders', 'thumbnail') 
        ->with(['cuisines:name,slug']) 
        ->where('status', 'live') 
        ->orderBy('number_of_orders', 'desc'); 

        // Apply filter for cuisine if provided
        if ($cuisineSlug) {
            $query->whereHas('cuisines', function ($q) use ($cuisineSlug) {
                $q->where('slug', $cuisineSlug);
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Get available cuisines with aggregated data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCuisineFilters()
    {
        return Cuisine::select('id', 'name', 'slug')
        ->withCount(['menus' => function ($query) {
            $query->where('status', 'live');
        }])
        ->orderByDesc('menus_count') // Sort by the count of live menus
        ->get();
    }
}
