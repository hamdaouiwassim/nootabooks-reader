<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClubSeeder extends Seeder
{
    public function run(): void
    {
        $clubs = [
            [
                'name' => 'أدب عربي معاصر',
                'category' => 'أدب عربي',
                'description' => 'نناقش أبرز الروايات العربية الحديثة أسبوعيًا، ونستضيف أحيانًا مؤلفين للحديث عن أعمالهم مباشرة مع الأعضاء. النادي مفتوح لكل محبي الأدب العربي المعاصر من كافة المستويات.',
                'rules' => "يُرجى تجنب حرق الأحداث (Spoilers) خارج الفصول المحددة للمناقشة\nالاحترام المتبادل في كل النقاشات والتعليقات\nالمشاركة الأسبوعية مُستحسنة لكن غير إلزامية\nيمكن اقتراح الكتاب القادم في نهاية كل شهر عبر التصويت",
                'book' => 'turab-al-mas',
                'owner_email' => 'layla.hassan@example.com',
                'owner_name' => 'ليلى حسن',
                'members' => ['mohamed.otaibi@example.com', 'sara.mahmoud@example.com'],
            ],
            [
                'name' => 'عشاق الخيال العلمي',
                'category' => 'أدب عالمي',
                'description' => 'لمحبي الخيال العلمي والروايات المستقبلية، نقرأ ونناقش أبرز أعمال هذا النوع الأدبي من كل أنحاء العالم.',
                'rules' => "يُرجى تجنب حرق الأحداث خارج الفصول المحددة\nالاحترام المتبادل في كل النقاشات",
                'book' => '1984',
                'owner_email' => 'omar.khaled@example.com',
                'owner_name' => 'عمر خالد',
                'members' => ['nour.hussein@example.com'],
            ],
            [
                'name' => 'روايات مترجمة',
                'category' => 'أدب عالمي',
                'description' => 'أفضل الروايات العالمية المترجمة للعربية، نختار كتابًا كل شهر من أدب مختلف حول العالم.',
                'rules' => "الاحترام المتبادل في كل النقاشات والتعليقات\nيمكن اقتراح الكتاب القادم عبر التصويت الشهري",
                'book' => 'the-alchemist',
                'owner_email' => 'sara.mahmoud@example.com',
                'owner_name' => 'سارة محمود',
                'members' => ['mohamed.otaibi@example.com'],
            ],
            [
                'name' => 'نادي الروايات البوليسية',
                'category' => 'روايات',
                'description' => 'نحل الألغاز ونناقش الجرائم في الروايات، لعشاق التشويق والإثارة والغموض.',
                'rules' => "يُرجى تجنب حرق الأحداث خارج الفصول المحددة\nالمشاركة الأسبوعية مُستحسنة لكن غير إلزامية",
                'book' => 'vertigo',
                'owner_email' => 'mohamed.otaibi@example.com',
                'owner_name' => 'محمد العتيبي',
                'members' => [],
            ],
        ];

        foreach ($clubs as $entry) {
            $owner = User::firstOrCreate(
                ['email' => $entry['owner_email']],
                ['name' => $entry['owner_name'], 'password' => Hash::make('password')]
            );

            $club = Club::firstOrCreate(
                ['name' => $entry['name']],
                [
                    'category' => $entry['category'],
                    'description' => $entry['description'],
                    'rules' => $entry['rules'],
                    'created_by' => $owner->id,
                ]
            );

            if (! $club->members()->where('user_id', $owner->id)->exists()) {
                $club->members()->attach($owner->id, ['role' => 'owner']);
            }

            foreach ($entry['members'] as $memberEmail) {
                $member = User::firstOrCreate(
                    ['email' => $memberEmail],
                    ['name' => ucfirst(explode('.', $memberEmail)[0]), 'password' => Hash::make('password')]
                );

                if (! $club->members()->where('user_id', $member->id)->exists()) {
                    $club->members()->attach($member->id, ['role' => 'member']);
                }
            }

            $book = Book::where('slug', $entry['book'])->first();
            if ($book && ! $club->books()->where('book_id', $book->id)->exists()) {
                $club->books()->attach($book->id, ['started_at' => now()->subWeeks(2)]);
            }
        }
    }
}
