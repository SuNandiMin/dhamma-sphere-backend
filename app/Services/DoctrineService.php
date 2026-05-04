<?php

namespace App\Services;

use App\Models\Doctrine;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DoctrineService
{
    public function list(array $filters): LengthAwarePaginator
    {
        return Doctrine::query()
            ->withCount('posts')
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where(function ($inner) use ($search): void {
                $inner->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            }))
            ->when($filters['topic'] ?? null, fn ($query, $topic) => $query->where('topic', $topic))
            ->when($filters['language'] ?? null, fn ($query, $language) => $query->where('language', $language))
            ->when(array_key_exists('featured', $filters), fn ($query) => $query->where('featured', (bool) $filters['featured']))
            ->latest()
            ->paginate(api_per_page($filters['per_page'] ?? null));
    }

    public function create(array $data): Doctrine
    {
        return Doctrine::query()->create($this->normalize($data));
    }

    public function update(Doctrine $doctrine, array $data): Doctrine
    {
        $doctrine->update($this->normalize($data, true));

        return $doctrine->refresh();
    }

    public function options(): array
    {
        return [
            'topics' => Doctrine::query()->select('topic')->distinct()->orderBy('topic')->pluck('topic'),
            'languages' => Doctrine::query()->select('language')->distinct()->orderBy('language')->pluck('language'),
            'sourceLanguages' => Doctrine::query()->select('source_language')->distinct()->orderBy('source_language')->pluck('source_language'),
        ];
    }

    private function normalize(array $data, bool $partial = false): array
    {
        $normalized = collect($data)->except(['sourceLanguage', 'aiAvailable'])->all();

        if (array_key_exists('sourceLanguage', $data)) {
            $normalized['source_language'] = $data['sourceLanguage'];
        } elseif (! $partial && ! array_key_exists('source_language', $normalized)) {
            $normalized['source_language'] = 'Pali';
        }

        if (array_key_exists('aiAvailable', $data)) {
            $normalized['ai_available'] = $data['aiAvailable'];
        } elseif (! $partial && ! array_key_exists('ai_available', $normalized)) {
            $normalized['ai_available'] = true;
        }

        if (! $partial && ! array_key_exists('featured', $normalized)) {
            $normalized['featured'] = false;
        }

        return $normalized;
    }
}
