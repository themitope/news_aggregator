<?php
namespace App\Services;

use App\Interfaces\NewsSourceInterface;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class NewsApiAdapter implements NewsSourceInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.newsapi.url'),
            'headers' => [
                'X-Api-Key' => config('services.newsapi.key')
            ]
        ]);
    }

    public function fetchArticles(): array
    {
        try {
            $response = $this->client->get('top-headlines', [
                'query' => [
                    'language' => config('constants.language'),
                    'pageSize' => config('constants.pagination_limit')
                ]
            ]);

            $articles = json_decode($response->getBody(), true)['articles'];

            return $this->transformArticles($articles);
        } catch (\Exception $e) {
            Log::error('NewsAPI fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    public function transformArticles(array $articles): array
    {
        return array_map(function ($article) {
            return [
                'title' => $article['title'],
                'content' => $article['content'],
                'source' => $article['source']['name'],
                'author' => $article['author'],
                'published_at' => Carbon::parse($article['publishedAt']),
                'url' => $article['url'],
                'image_url' => [$article['urlToImage']]
            ];
        }, $articles);
    }
}