<?php

use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
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
                'name'      => 'Image 1',
                'file'      => 'C:\xampp74\htdocs\surplus-syarif\storage\images/63e8ebbf3eb43.png',
                'enable'      => 1,
            ],
            [
                'name'      => 'Image 2',
                'file'      => 'C:\xampp74\htdocs\surplus-syarif\storage\images/63e8ebc3ddd26.png',
                'enable'      => 1,
            ],
            [
                'name'      => 'Image 3',
                'file'      => 'C:\xampp74\htdocs\surplus-syarif\storage\images/63e8ebc4c1f77.png',
                'enable'      => 0,
            ],
            [
                'name'      => 'Image 4',
                'file'      => 'C:\xampp74\htdocs\surplus-syarif\storage\images/63e8ec03f08c0.png',
                'enable'      => 1,
            ],
        ];
        
        foreach($data as $key => $v){
            \App\Models\Image::updateOrCreate($v);
        }
    }
}
