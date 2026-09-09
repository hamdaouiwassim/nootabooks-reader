<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'روايات', 'slug' => 'novels', 'icon' => 'fa-bookmark', 'color' => 'cat-purple'],
            ['name' => 'أدب عربي', 'slug' => 'arabic-literature', 'icon' => 'fa-book', 'color' => 'cat-navy'],
            ['name' => 'أدب عالمي', 'slug' => 'world-literature', 'icon' => 'fa-earth-africa', 'color' => 'cat-teal'],
            ['name' => 'تنمية ذاتية', 'slug' => 'self-development', 'icon' => 'fa-bell', 'color' => 'cat-brown'],
            ['name' => 'تاريخ', 'slug' => 'history', 'icon' => 'fa-landmark', 'color' => 'cat-navy'],
            ['name' => 'علوم', 'slug' => 'science', 'icon' => 'fa-atom', 'color' => 'cat-green'],
            ['name' => 'أطفال', 'slug' => 'kids', 'icon' => 'fa-child', 'color' => 'cat-rose'],
            ['name' => 'دين وروحانيات', 'slug' => 'religion-spirituality', 'icon' => 'fa-mosque', 'color' => 'cat-gold'],
            ['name' => 'شعر', 'slug' => 'poetry', 'icon' => 'fa-feather', 'color' => 'cat-teal'],
            ['name' => 'تكنولوجيا', 'slug' => 'technology', 'icon' => 'fa-microchip', 'color' => 'cat-teal'],
            ['name' => 'فلسفة', 'slug' => 'philosophy', 'icon' => 'fa-leaf', 'color' => 'cat-green'],
            ['name' => 'تنمية ذهنية', 'slug' => 'mental-development', 'icon' => 'fa-brain', 'color' => 'cat-green'],
            ['name' => 'قصص قصيرة', 'slug' => 'short-stories', 'icon' => 'fa-file-lines', 'color' => 'cat-navy'],
            ['name' => 'اقتصاد وأعمال', 'slug' => 'economics-business', 'icon' => 'fa-chart-line', 'color' => 'cat-navy'],
            ['name' => 'سياسة', 'slug' => 'politics', 'icon' => 'fa-landmark-dome', 'color' => 'cat-navy'],
            ['name' => 'فنون وتصميم', 'slug' => 'arts-design', 'icon' => 'fa-palette', 'color' => 'cat-rose'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
