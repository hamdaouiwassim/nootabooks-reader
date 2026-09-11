<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $layla = User::where('email', 'layla.hassan@example.com')->first();
        $mohamed = User::where('email', 'mohamed.otaibi@example.com')->first();
        $sara = User::where('email', 'sara.mahmoud@example.com')->first();
        $arabicLitClub = Club::where('name', 'أدب عربي معاصر')->first();

        // ---- A couple of club-scoped discussions ----
        if ($layla && $arabicLitClub) {
            $reminder = Discussion::firstOrCreate([
                'user_id' => $mohamed?->id ?? $layla->id,
                'club_id' => $arabicLitClub->id,
                'body' => 'تذكير بموعد المناقشة الأسبوعية يوم الخميس الساعة 8 مساءً، هنتكلم عن الفصول من 1 إلى 10. جهزوا أسئلتكم! #نادي_القراءة',
            ]);

            $progress = Discussion::firstOrCreate([
                'user_id' => $layla->id,
                'club_id' => $arabicLitClub->id,
                'book_id' => $arabicLitClub->currentBook()?->id,
                'body' => 'وصلت لمنتصف الرواية، والتحول اللي حصل للشخصية الرئيسية غير متوقع خالص! حد وصل لنفس النقطة؟',
            ]);
        }

        // ---- Threaded comments on the "blue elephant" discussion ----
        $blueElephantPost = Discussion::whereNull('club_id')
            ->where('body', 'like', '%الفيل الأزرق%')
            ->first();

        if ($blueElephantPost && $mohamed && $sara) {
            $parent = $blueElephantPost->comments()->firstOrCreate([
                'user_id' => $mohamed->id,
                'parent_id' => null,
                'body' => 'النهاية كانت متوقعة شوية بالنسبة لي، بس طريقة السرد اللي وصلنا بيها ليها كانت هي الأهم. أحمد مراد بيتقن بناء التوتر النفسي فعلاً.',
            ]);

            $parent->replies()->firstOrCreate([
                'discussion_id' => $blueElephantPost->id,
                'user_id' => $sara->id,
                'body' => 'متفقة معاك تمامًا، خصوصًا مشاهد المستشفى في النص الأول.',
            ]);

            $blueElephantPost->comments()->firstOrCreate([
                'user_id' => $sara->id,
                'parent_id' => null,
                'body' => 'قرأت الجزء الأول والتاني، وشخصيًا حسيت إن الأول أقوى في البناء النفسي للشخصية. مين قرأهم الاتنين يشاركني رأيه؟',
            ]);
        }
    }
}
