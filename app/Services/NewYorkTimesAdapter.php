<?php
namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class NewYorkTimesAdapter
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.new_york_times.url'),
        ]);
    }

    public function fetchArticles(): array
    {
        try {
            $today = Carbon::now();
            $year = $today->format('Y');
            $month = $today->format('n');

            $response = $this->client->get("svc/archive/v1/{$year}/{$month}.json", [
                'query' => [
                    'api-key' => config('services.new_york_times.key'),
                ]
            ]);

            $articles = json_decode($response->getBody(), true)['response']['docs'];

            return $this->transformArticles($articles);
        } catch (\Exception $e) {
            Log::error('New York Times news fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    protected function transformArticles(array $articles): array
    {
        return array_map(function ($article) {
            return [
                'title' => $article['headline']['main'],
                'content' => $article['snippet'],
                'source' => $article['source'],
                'author' => $article['author'] ?? null,
                'category' => $article['section_name'],
                'published_at' => Carbon::parse($article['pub_date']),
                'url' => $article['web_url'],
                'image_url' => collect($article['multimedia'])->pluck('url')->toArray(),
            ];
        }, $articles);
    }
}