<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\MenuService;

class GetMenusTest extends TestCase
{
    /**
     * test get menu.
     */
    public function test_get_menu(): void
    {
        $service = new MenuService();
        $result = $service->getMenus('italian', 5);
    
        $this->assertNotEmpty($result);
        $this->assertCount(5, $result->items());
    }
}