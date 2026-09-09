<?php

namespace Database\Seeders;

use App\Models\Writer;
use Illuminate\Database\Seeder;

class WriterSeeder extends Seeder
{
    public function run(): void
    {
        $writers = [
            [
                'slug' => 'naguib-mahfouz',
                'name' => 'نجيب محفوظ',
                'photo' => 'https://i.pravatar.cc/240?img=59',
                'genre_tag' => 'أدب عربي كلاسيكي',
                'bio' => 'روائي مصري، حائز على جائزة نوبل للآداب عام 1988، ويُعد أحد أعمدة الرواية العربية الحديثة. تركت أعماله مثل "الثلاثية" و"أولاد حارتنا" أثرًا عميقًا في الأدب العربي والعالمي.',
                'followers_count' => 289000,
                'rating_average' => 4.8,
                'joined_year' => 1939,
                'is_featured' => true,
            ],
            [
                'slug' => 'ahmed-mourad',
                'name' => 'أحمد مراد',
                'photo' => 'https://i.pravatar.cc/240?img=14',
                'genre_tag' => 'إثارة وغموض',
                'bio' => 'روائي وسيناريست وفوتوغرافي مصري، من أبرز كتاب الرواية البوليسية والنفسية في الأدب العربي المعاصر. بدأ مسيرته الأدبية عام 2008 وتحولت عدة أعمال له إلى أفلام سينمائية ناجحة.',
                'followers_count' => 152000,
                'rating_average' => 4.6,
                'joined_year' => 2008,
                'is_featured' => false,
            ],
            [
                'slug' => 'ahmed-khaled-tawfik',
                'name' => 'أحمد خالد توفيق',
                'photo' => 'https://i.pravatar.cc/240?img=68',
                'genre_tag' => 'رعب وخيال علمي',
                'bio' => 'رائد أدب الرعب والخيال العلمي في المكتبة العربية.',
                'followers_count' => 198000,
                'rating_average' => 4.5,
                'joined_year' => 1993,
                'is_featured' => false,
            ],
            [
                'slug' => 'ihsan-abdel-quddous',
                'name' => 'أحسان عبد القدوس',
                'photo' => 'https://i.pravatar.cc/240?img=52',
                'genre_tag' => 'أدب عربي كلاسيكي',
                'bio' => 'رائد الرواية الرومانسية والاجتماعية في الأدب المصري.',
                'followers_count' => 97000,
                'rating_average' => 4.4,
                'joined_year' => 1941,
                'is_featured' => false,
            ],
            [
                'slug' => 'amr-abdelhamid',
                'name' => 'عمرو عبد الحميد',
                'photo' => 'https://i.pravatar.cc/240?img=13',
                'genre_tag' => 'أدب عربي معاصر',
                'bio' => 'روايات فلسفية وتشويقية حظيت بانتشار واسع بين الشباب.',
                'followers_count' => 134000,
                'rating_average' => 4.7,
                'joined_year' => 2012,
                'is_featured' => false,
            ],
            [
                'slug' => 'youssef-ziedan',
                'name' => 'يوسف زيدان',
                'photo' => 'https://i.pravatar.cc/240?img=33',
                'genre_tag' => 'أدب عربي معاصر',
                'bio' => 'روائي ومفكر إسلامي، صاحب رواية "عزازيل" الشهيرة.',
                'followers_count' => 88000,
                'rating_average' => 4.6,
                'joined_year' => 1998,
                'is_featured' => false,
            ],
            [
                'slug' => 'gabriel-garcia-marquez',
                'name' => 'غابرييل غارسيا ماركيز',
                'photo' => 'https://i.pravatar.cc/240?img=8',
                'genre_tag' => 'أدب عالمي',
                'bio' => 'رائد الواقعية السحرية، حائز على جائزة نوبل للآداب.',
                'followers_count' => 210000,
                'rating_average' => 4.7,
                'joined_year' => 1955,
                'is_featured' => false,
            ],
            [
                'slug' => 'paulo-coelho',
                'name' => 'باولو كويلو',
                'photo' => 'https://i.pravatar.cc/240?img=51',
                'genre_tag' => 'أدب عالمي',
                'bio' => 'روائي برازيلي عالمي الشهرة، صاحب رواية "الخيميائي".',
                'followers_count' => 176000,
                'rating_average' => 4.6,
                'joined_year' => 1982,
                'is_featured' => false,
            ],
            [
                'slug' => 'george-orwell',
                'name' => 'جورج أورويل',
                'photo' => 'https://i.pravatar.cc/240?img=12',
                'genre_tag' => 'أدب عالمي',
                'bio' => 'كاتب بريطاني وناقد سياسي، صاحب رواية "1984" الخالدة.',
                'followers_count' => 245000,
                'rating_average' => 4.8,
                'joined_year' => 1933,
                'is_featured' => false,
            ],
            [
                'slug' => 'tayeb-salih',
                'name' => 'الطيب صالح',
                'photo' => 'https://i.pravatar.cc/240?img=53',
                'genre_tag' => 'أدب عربي كلاسيكي',
                'bio' => 'روائي سوداني عالمي، صاحب "موسم الهجرة إلى الشمال".',
                'followers_count' => 76000,
                'rating_average' => 4.7,
                'joined_year' => 1966,
                'is_featured' => false,
            ],
            [
                'slug' => 'ghassan-kanafani',
                'name' => 'غسان كنفاني',
                'photo' => 'https://i.pravatar.cc/240?img=15',
                'genre_tag' => 'أدب عربي كلاسيكي',
                'bio' => 'روائي وصحفي فلسطيني، من أبرز الأصوات الأدبية في القضية الفلسطينية، صاحب "رجال في الشمس".',
                'followers_count' => 69000,
                'rating_average' => 4.5,
                'joined_year' => 1956,
                'is_featured' => false,
            ],
            [
                'slug' => 'antoine-de-saint-exupery',
                'name' => 'أنطوان دو سانت-إكزوبيري',
                'photo' => 'https://i.pravatar.cc/240?img=17',
                'genre_tag' => 'أدب عالمي',
                'bio' => 'كاتب وطيار فرنسي، صاحب الحكاية الخالدة "الأمير الصغير".',
                'followers_count' => 158000,
                'rating_average' => 4.9,
                'joined_year' => 1926,
                'is_featured' => false,
            ],
        ];

        foreach ($writers as $writer) {
            Writer::updateOrCreate(['slug' => $writer['slug']], $writer);
        }
    }
}
