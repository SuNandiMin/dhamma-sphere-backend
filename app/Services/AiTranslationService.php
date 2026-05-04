<?php

namespace App\Services;

use App\Models\Doctrine;

class AiTranslationService
{
    public function translate(Doctrine $doctrine, string $language, ?string $tone = null): array
    {
        return [
            'translation' => "AI-assisted {$language} rendering of \"{$doctrine->title}\": {$doctrine->excerpt} This is a mock AI response ready to be replaced with an OpenAI integration.",
            'provider' => 'mock',
            'tone' => $tone ?? 'study-friendly',
        ];
    }
}
