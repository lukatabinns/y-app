<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\MenuService;
use App\Http\Requests\GetMenusRequest;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Get menus with filters and pagination.
     *
     * @param GetMenusRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMenus(GetMenusRequest $request)
    {
        $cuisineSlug = $request->query('cuisineSlug');
        $perPage = $request->query('perPage', 10);

        $menus = $this->menuService->getMenus($cuisineSlug, $perPage);
        $cuisines = $this->menuService->getCuisineFilters();

        return response()->json([
            'data' => $menus->items(),
            'pagination' => [
                'total' => $menus->total(),
                'per_page' => $menus->perPage(),
                'current_page' => $menus->currentPage(),
                'last_page' => $menus->lastPage(),
            ],
            'filters' => [
                'cuisines' => $cuisines,
            ],
        ]);
    }
}
