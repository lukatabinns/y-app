<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Menu;
use App\Models\Cuisine;
use App\Models\MenuGroup;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class CollectMenuData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:collect-menu-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect menus data from the API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // URL of the API, retrieved from the .env file for configurability
        $url = env('SET_MENUS_API_URL');
        $currentPage = 1;

        $rateLimitPerSecond = 1;

        do {
            
            // RateLimiter ensures that we make only one request per second
            RateLimiter::attempt('api-collect-menus', $rateLimitPerSecond, function () use ($url, $currentPage) {
                
                // Fetch data from the API for the current page
                $response = Http::get("{$url}?page={$currentPage}");
                $data = $response->json();

                // Process the "data" key from the API response if it exists
                if (isset($data['data'])) {
                    $this->processMenus($data['data']);
                }
               
                // Output success message for the current page
                $this->info("Page {$currentPage} processed successfully.");
                $currentPage++;
            });

        } while (isset($data['links']['next']));

        $this->info('Collect menus data successfully done.');
    }

    /**
     * Process and store menu data into the database.
     *
     * @param array $menus The array of set menus fetched from the API
     */
    private function processMenus(array $menus)
    {
        foreach ($menus as $menuData) {

            // Update or create a SetMenu record in the database
            $menu = Menu::updateOrCreate(
                ['name' => $menuData['name']],
                [
                    'description' => $menuData['description'],
                    'image' => $menuData['image'],
                    'thumbnail' => $menuData['thumbnail'],
                    'is_vegan' => $menuData['is_vegan'],
                    'is_vegetarian' => $menuData['is_vegetarian'],
                    'price_per_person' => $menuData['price_per_person'],
                    'min_spend' => $menuData['min_spend'],
                    'is_seated' => $menuData['is_seated'],
                    'is_standing' => $menuData['is_standing'],
                    'is_canape' => $menuData['is_canape'],
                    'is_mixed_dietary' => $menuData['is_mixed_dietary'],
                    'is_meal_prep' => $menuData['is_meal_prep'],
                    'is_halal' => $menuData['is_halal'],
                    'is_kosher' => $menuData['is_kosher'],
                    'number_of_orders' => $menuData['number_of_orders'],
                    'available' => $menuData['available'],
                ]
            );

            // Process cuisines and associate them with the Menu
            foreach ($menuData['cuisines'] as $cuisineData) {
                $cuisine = Cuisine::firstOrCreate(
                    ['name' => $cuisineData['name']],
                    [
                        'slug' => Str::slug($cuisineData['name']),
                    ]
                );
                $menu->cuisines()->syncWithoutDetaching($cuisine->id);
            }

            // Process menu groups
            MenuGroup::updateOrCreate(
                [
                    'menu_id' => $menu->id,
                ],
                [
                    'menu_id' => $menu->id,
                    'dishes_count' => $menuData['groups']['dishes_count'],
                    'selectable_dishes_count' => $menuData['groups']['selectable_dishes_count'],
                    'groups' => json_encode($menuData['groups']['groups'] ?? []), // Store the "groups" data
                ]
            );
        }
    }
}
