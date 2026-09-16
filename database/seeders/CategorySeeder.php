<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'روايات', 'slug' => 'novels', 'color' => 'cat-purple'],
            ['name' => 'أدب عربي', 'slug' => 'arabic-literature', 'color' => 'cat-navy'],
            ['name' => 'أدب عالمي', 'slug' => 'world-literature', 'color' => 'cat-teal'],
            ['name' => 'تنمية ذاتية', 'slug' => 'self-development', 'color' => 'cat-brown'],
            ['name' => 'تاريخ', 'slug' => 'history', 'color' => 'cat-navy'],
            ['name' => 'علوم', 'slug' => 'science', 'color' => 'cat-green'],
            ['name' => 'أطفال', 'slug' => 'kids', 'color' => 'cat-rose'],
            ['name' => 'دين وروحانيات', 'slug' => 'religion-spirituality', 'color' => 'cat-gold'],
            ['name' => 'شعر', 'slug' => 'poetry', 'color' => 'cat-teal'],
            ['name' => 'تكنولوجيا', 'slug' => 'technology', 'color' => 'cat-teal'],
            ['name' => 'فلسفة', 'slug' => 'philosophy', 'color' => 'cat-green'],
            ['name' => 'تنمية ذهنية', 'slug' => 'mental-development', 'color' => 'cat-green'],
            ['name' => 'قصص قصيرة', 'slug' => 'short-stories', 'color' => 'cat-navy'],
            ['name' => 'اقتصاد وأعمال', 'slug' => 'economics-business', 'color' => 'cat-navy'],
            ['name' => 'سياسة', 'slug' => 'politics', 'color' => 'cat-navy'],
            ['name' => 'فنون وتصميم', 'slug' => 'arts-design', 'color' => 'cat-rose'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
