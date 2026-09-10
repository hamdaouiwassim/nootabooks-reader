<?php

namespace Database\Seeders;

use App\Models\Quote;
use Illuminate\Database\Seeder;

class QuoteSeeder extends Seeder
{
    public function run(): void
    {
        $quotes = [
            ['text' => 'الكتب هي نوافذ نرى من خلالها عوالم أخرى', 'author' => "خالد الأحمدي"],
            ['text' => 'القراءة رحلة لا تنتهي بين صفحات لا حدود لها', 'author' => "Paul Jiron"],
            ['text' => 'كل كتاب تقرأه يجعلك إنسانًا مختلفًا', 'author' => null],
        ];

        foreach ($quotes as $quote) {
            Quote::firstOrCreate(['text' => $quote['text']], $quote);
        }
    }
}
