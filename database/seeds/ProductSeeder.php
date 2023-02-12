<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name'      => 'Facade',
                'description'      => 'no description',
                'enable'      => 1,
            ],
            [
                'name'      => 'BCS Building',
                'description'      => 'no description',
                'enable'      => 1,
            ],
            [
                'name'      => 'Box Girder',
                'description'      => 'no description',
                'enable'      => 1,
            ],
            [
                'name'      => 'Arch Girder',
                'description'      => 'no description',
                'enable'      => 1,
            ],
            [
                'name'      => 'Spun Pile',
                'description'      => 'no description',
                'enable'      => 1,
            ],
            [
                'name'      => 'Square Pile',
                'description'      => 'no description',
                'enable'      => 1,
            ],
            [
                'name'      => 'Planter Box',
                'description'      => 'no description',
                'enable'      => 1,
            ],
        ];
        
        foreach($data as $key => $v){
            \App\Models\Product::updateOrCreate($v);
        }
    }
}
