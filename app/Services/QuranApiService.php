<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class QuranApiService
{
    private string $baseUrl = 'https://equran.id/api/v2';

    public function getAllSurah(): array
    {
        return Cache::remember('quran_all_surah', 1440 * 60, function () {
            try {
                $response = Http::timeout(10)->get("{$this->baseUrl}/surat");
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['data']) && is_array($data['data'])) {
                        return $data['data'];
                    }
                }
                return [];
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    public function getSurah(int $nomor): ?array
    {
        if ($nomor < 1 || $nomor > 114) return null;

        return Cache::remember("quran_surah_{$nomor}", 1440 * 60, function () use ($nomor) {
            try {
                $response = Http::timeout(15)->get("{$this->baseUrl}/surat/{$nomor}");
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['data']) && is_array($data['data'])) {
                        return $data['data'];
                    }
                }
                return null;
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    public function clearCache(): void
    {
        Cache::forget('quran_all_surah');
        for ($i = 1; $i <= 114; $i++) {
            Cache::forget("quran_surah_{$i}");
        }
    }
}