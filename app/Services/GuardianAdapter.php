<?php
namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GuardianAdapter
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.guardian.url'),
        ]);
    }

    public function fetchArticles(): array
    {
        try {
            $response = $this->client->get('search', [
                'query' => [
                    'api-key' => config('services.guardian.key'),
                    'page-size' => config('constants.pagination_limit'),
                    'show-fields' => 'body',
                ]
            ]);

            $articles = json_decode($response->getBody(), true)['response']['results'];

            return $this->transformArticles($articles);
        } catch (\Exception $e) {
            Log::error('Guardian news fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    protected function transformArticles(array $articles): array
    {
        return array_map(function ($article) {
            return [
                'title' => $article['webTitle'],
                'content' => $article['fields']['body'],
                'source' => 'The Guardian',
                'category' => $article['sectionName'],
                'author' => $article['author'] ?? null,
                'published_at' => Carbon::parse($article['webPublicationDate']),
                'url' => $article['webUrl'],
            ];
        }, $articles);
    }
}