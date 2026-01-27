<?php

namespace Database\Seeders;

use App\Models\BlockedWord;
use Illuminate\Database\Seeder;

class BlockedWordSeeder extends Seeder
{
    public function run(): void
    {
        $words = [
            // Español - Alta severidad
            ['word' => 'puto', 'severity' => 'high'],
            ['word' => 'puta', 'severity' => 'high'],
            ['word' => 'mierda', 'severity' => 'high'],
            ['word' => 'carajo', 'severity' => 'high'],
            ['word' => 'coño', 'severity' => 'high'],
            ['word' => 'verga', 'severity' => 'high'],
            ['word' => 'pendejo', 'severity' => 'high'],

            // Español - Media severidad
            ['word' => 'tonto', 'severity' => 'medium'],
            ['word' => 'idiota', 'severity' => 'medium'],
            ['word' => 'basura', 'severity' => 'medium'],

            // English - High severity
            ['word' => 'fuck', 'severity' => 'high'],
            ['word' => 'shit', 'severity' => 'high'],
            ['word' => 'bitch', 'severity' => 'high'],
            ['word' => 'asshole', 'severity' => 'high'],

            // English - Medium severity
            ['word' => 'damn', 'severity' => 'medium'],
            ['word' => 'hell', 'severity' => 'medium'],
            ['word' => 'stupid', 'severity' => 'medium'],
        ];

        foreach ($words as $word) {
            BlockedWord::firstOrCreate(
                ['word' => strtolower($word['word'])],
                ['severity' => $word['severity'], 'is_active' => true]
            );
        }
    }
}
