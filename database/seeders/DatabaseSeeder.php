<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@servegod.test'],
            [
                'name' => 'Ava Sterling',
                'username' => 'ava',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'bio' => 'Founder and visual storyteller.',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'editor@servegod.test'],
            [
                'name' => 'Noah Vale',
                'username' => 'noah',
                'password' => Hash::make('password'),
                'role' => 'editor',
                'bio' => 'Editorial curator for the daily feed.',
                'is_active' => true,
            ]
        );

        foreach ([
            'Travel',
            'People',
            'Moments',
            'Places',
        ] as $categoryName) {
            Category::updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                [
                    'name' => $categoryName,
                    'slug' => Str::slug($categoryName),
                    'description' => null,
                    'accent_color' => null,
                    'cover_url' => null,
                ]
            );
        }

        $tagNames = [
            'People',
            'Daily',
            'Moments',
            'Mood',
            'Vibes',
            'Story',
            'Random',
            'Photos',
            'Clips',
        ];

        Tag::query()
            ->whereNotIn('slug', array_map(fn (string $name) => Str::slug($name), $tagNames))
            ->delete();

        foreach ($tagNames as $tagName) {
            Tag::updateOrCreate(
                ['slug' => Str::slug($tagName)],
                [
                    'name' => $tagName,
                    'slug' => Str::slug($tagName),
                ]
            );
        }
    }
}
