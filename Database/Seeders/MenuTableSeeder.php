<?php

namespace Modules\Email\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\Menu;
use Modules\Admin\Models\MenuItem;

class MenuTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $menuItems = [
            'leftAdminMenu' => [
                [
                    'name'            => 'Emails',
                    'icon'            => 'fa fa-envelope',
                    'type'            => 'url',
                    'value'           => '#',
                    'module'          => 'Email',
                    'is_active'       => 1,
                    'parameters'      => json_encode([]),
                    'active_resolver' => 'admin::automated_emails.*,admin::campaigns.*,admin::subscribers.*',
                    'children'        => [
                        [
                            'name'            => 'Automated emails',
                            'type'            => 'route',
                            'value'           => 'admin::automated_emails.index',
                            'module'          => '',
                            'is_active'       => 1,
                            'active_resolver' => 'admin::automated_emails.*',
                            'parameters'      => json_encode([])
                        ],
                        [
                            'name'            => 'Campaigns',
                            'type'            => 'route',
                            'value'           => 'admin::campaigns.index',
                            'module'          => '',
                            'is_active'       => 1,
                            'active_resolver' => 'admin::campaigns.*',
                            'parameters'      => json_encode([])
                        ],
                        [
                            'name'            => 'Subscribers',
                            'type'            => 'route',
                            'value'           => 'admin::subscribers.index',
                            'module'          => '',
                            'is_active'       => 1,
                            'active_resolver' => 'admin::subscribers.*',
                            'parameters'      => json_encode([])
                        ],
                    ]
                ],
            ]
        ];

        foreach ($menuItems as $name => $items) {
            $menu = Menu::firstOrCreate([
                'name' => $name
            ]);

            foreach ($items as $item) {
                $item['menu_id'] = $menu->id;
                $item['parent_id'] = null;
                $parentItem = MenuItem::firstOrCreate(array_except($item, 'children'));

                if (isset($item['children'])) {
                    foreach ($item['children'] as $child) {
                        $child['parent_id'] = $parentItem->id;
                        $child['menu_id'] = $menu->id;
                        MenuItem::firstOrCreate($child);
                    }
                }

            }
        }
    }
}
