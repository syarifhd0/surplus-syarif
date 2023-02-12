<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
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
                'name'      => 'Building',
                'enable'      => 1,
            ],
            [
                'name'      => 'Infrastructure',
                'enable'      => 1,
            ],
            [
                'name'      => 'Pile',
                'enable'      => 1,
            ],
            [
                'name'      => 'Understructure',
                'enable'      => 1,
            ],
        ];
        
        foreach($data as $key => $v){
            \App\Models\Category::updateOrCreate($v);
        }
    }
}
