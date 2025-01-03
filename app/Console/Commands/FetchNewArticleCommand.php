<?php

namespace App\Console\Commands;

use App\Services\NewsAggregatorService;
use Illuminate\Console\Command;

class FetchNewArticleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-new-article-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command fetches news article from different sources hourly';

    /**
     * Execute the console command.
     */
    public function handle(NewsAggregatorService $newsAggregatorService)
    {
        $this->info('Fetching news articles...');
        $newsAggregatorService->fetchAndStoreArticles();
        $this->info('Completed fetching news articles');
    }
}
