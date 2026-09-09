<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $book = Book::where('slug', 'blue-elephant')->first();

        if (! $book) {
            return;
        }

        $reviews = [
            [
                'name' => 'سارة محمود',
                'email' => 'sara.mahmoud@example.com',
                'rating' => 5,
                'comment' => 'من أفضل الروايات العربية التي قرأتها هذا العام، حبكة قوية وأسلوب سردي مشوق لا يمل القارئ من متابعته حتى النهاية.',
            ],
            [
                'name' => 'محمد العتيبي',
                'email' => 'mohamed.otaibi@example.com',
                'rating' => 4,
                'comment' => 'أحمد مراد يجيد بناء الغموض بطريقة تجعلك غير قادر على ترك الكتاب. النهاية كانت مفاجئة فعلاً.',
            ],
            [
                'name' => 'ليلى حسن',
                'email' => 'layla.hassan@example.com',
                'rating' => 5,
                'comment' => 'تجربة قراءة مختلفة تمامًا، أسلوب الكاتب في وصف الحالة النفسية للبطل كان مؤثرًا جدًا.',
            ],
        ];

        foreach ($reviews as $entry) {
            $user = User::firstOrCreate(
                ['email' => $entry['email']],
                ['name' => $entry['name'], 'password' => Hash::make('password')]
            );

            Review::updateOrCreate(
                ['user_id' => $user->id, 'book_id' => $book->id],
                ['rating' => $entry['rating'], 'comment' => $entry['comment']]
            );
        }
    }
}
