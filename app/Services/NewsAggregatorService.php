<?php

namespace App\Services;

use App\Models\Article;

class NewsAggregatorService
{
    protected $sources;

    public function __construct(
        private readonly ArticleService $articleService
    )
    {
        $this->sources = [
            new NewsApiAdapter(),
            new GuardianAdapter(),
            new NewYorkTimesAdapter()
        ];
    }

    public function fetchAndStoreArticles()
    {
        foreach ($this->sources as $source) {
            $articles = $source->fetchArticles();
            
            foreach ($articles as $article) {
                $this->articleService->updateOrCreate([
                    'url' => $article['url']
                ],$article);
            }
        }
    }
}