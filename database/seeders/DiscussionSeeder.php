<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DiscussionSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'name' => 'سارة محمود',
                'email' => 'sara.mahmoud@example.com',
                'book' => 'blue-elephant',
                'body' => 'انتهيت للتو من قراءة "الفيل الأزرق" لأحمد مراد، والله الحبكة كانت مشوقة جدًا لدرجة إني ما قدرت أسيب الكتاب! رأيكم إيه في النهاية؟ حسيت إنها مفاجئة أكتر من اللازم 😅',
            ],
            [
                'name' => 'محمد العتيبي',
                'email' => 'mohamed.otaibi@example.com',
                'book' => '1984',
                'body' => 'هل تعتقدون أن رواية "1984" لجورج أورويل أصبحت أكثر واقعية في عصرنا الحالي؟ أشعر أن كثيرًا مما تنبأ به الكاتب عن المراقبة أصبح جزءًا من حياتنا اليومية دون أن ننتبه.',
            ],
            [
                'name' => 'ليلى حسن',
                'email' => 'layla.hassan@example.com',
                'book' => 'turab-al-mas',
                'body' => 'نادي "أدب عربي معاصر" هيبدأ مناقشة رواية جديدة الأسبوع الجاي، مين حابب ينضم لينا؟ هنختار بين "تراب الماس" و"فيرتيجو" لنفس الكاتب. صوتوا في التعليقات 👇',
            ],
            [
                'name' => 'عمر خالد',
                'email' => 'omar.khaled@example.com',
                'book' => 'azazeel',
                'body' => '"عزازيل" ليوسف زيدان من أعمق الروايات العربية التي قرأتها، الأسلوب التاريخي ممزوج بصراع داخلي مؤثر جدًا. من قرأها ويحب يناقشها بعمق أكتر يا ريت يتواصل معايا.',
            ],
            [
                'name' => 'نور الدين حسين',
                'email' => 'nour.hussein@example.com',
                'book' => null,
                'body' => 'أي كتاب تنصحوني أبدأ بيه هذا الأسبوع؟ أبحث عن شيء مختلف عن روتيني المعتاد في القراءة 📚',
            ],
        ];

        foreach ($posts as $entry) {
            $user = User::firstOrCreate(
                ['email' => $entry['email']],
                ['name' => $entry['name'], 'password' => Hash::make('password')]
            );

            $book = $entry['book'] ? Book::where('slug', $entry['book'])->first() : null;

            Discussion::firstOrCreate([
                'user_id' => $user->id,
                'book_id' => $book?->id,
                'body' => $entry['body'],
            ]);
        }
    }
}
