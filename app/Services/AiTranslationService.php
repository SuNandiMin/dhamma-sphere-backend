<?php

namespace App\Services;

use App\Models\Doctrine;
use OpenAI;

class AiTranslationService
{
    public function translate(Doctrine $doctrine, string $language, ?string $tone = null): array
    {
        try {
            $client = OpenAI::client(config('services.openai.key'));
    
            $prompt = "Translate the following Buddhist doctrine into {$language}.
            Tone: " . ($tone ?? 'study-friendly') . ".
            Keep meaning accurate and respectful.
            
            Title: {$doctrine->title}
            Content: {$doctrine->content}";
            
                $response = $client->chat()->create([
                    'model' => 'gpt-4.1-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert translator of Buddhist teachings.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                ]);
        
            return [
                'translation' => $response->choices[0]->message->content,
                'provider' => 'openai',
                'tone' => $tone ?? 'study-friendly',
            ];
        } catch (\Exception $e) {
            return [
                'translation' => 'Error: ' . $e->getMessage(),
                'provider' => 'openai',
                'tone' => $tone ?? 'study-friendly',
            ];
        }
    }

    public function searchSuggestion(string $query): string
    {
        try {
            $client = \OpenAI::client(config('services.openai.key'));
    
            $response = $client->chat()->create([
                'model' => 'gpt-4.1-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a Buddhist assistant.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "User searched: {$query}. Suggest related Buddhist teachings."
                    ]
                ],
            ]);
    
            return $response->choices[0]->message->content ?? 'No suggestion available.';
        } catch (\Throwable $e) {
            // Log error for debugging
            \Log::error('AI search error: ' . $e->getMessage());
            return $e->getMessage();
            // return 'AI suggestion is currently unavailable.';
        }
    }
}
