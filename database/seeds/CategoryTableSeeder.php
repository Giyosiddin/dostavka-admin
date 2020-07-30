<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =[
            [
              'title'=> "Electronic items",
              'slug'=> "electronic-items",
              'image'=> "images/category/blue/cpu.svg",
            ],
            [
              'title'=> "Auto and motors",
              'slug' => "auto-motors",
              'image'=> "images/category/blue/car.svg",
            ],
            [
              'title'=> "Sports and outdoor",
              'slug' => "sports-and-outdoor",
              'image'=> "images/category/blue/ball.svg",
            ],
            [
              'title'=> "Home items",
              'slug'=>'home-items',
              'image'=> "images/category/blue/homeitem.svg",
            ],
            [
              'title'=> "Books and magazines ",
              'slug' =>'books-and-magazines',
              'image'=> "images/category/blue/book.svg",
            ]
          ];
        foreach ($data as $item) {
            \App\Category::updateOrCreate(['title' => $item['title']],$item);           
        }
    }
}
