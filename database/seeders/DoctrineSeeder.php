<?php

namespace Database\Seeders;

use App\Models\Doctrine;
use Illuminate\Database\Seeder;

class DoctrineSeeder extends Seeder
{
    public function run(): void
    {
        $rows = config('doctrines', []);

        if (! is_array($rows)) {
            return;
        }

        foreach ($rows as $row) {
            if (! is_array($row) || ! isset($row['title'], $row['topic'], $row['excerpt'], $row['content'])) {
                continue;
            }

            Doctrine::query()->updateOrCreate(
                [
                    'title' => $row['title'],
                    'topic' => $row['topic'],
                ],
                [
                    'source_language' => $row['source_language'] ?? 'Pali',
                    'language' => $row['language'] ?? 'Burmese',
                    'excerpt' => $row['excerpt'],
                    'content' => $row['content'],
                    'translator' => $row['translator'] ?? 'Dhamma Sphere Library',
                    'ai_available' => $row['ai_available'] ?? true,
                    'featured' => $row['featured'] ?? false,
                ]
            );
        }
    }
}
