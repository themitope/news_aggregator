<?php

namespace App\Repository;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ArticleRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Configure the Model
     **/
    public function __construct()
    {
        $this->model = new Article();
    }

    /**
     * Create model record
     *
     * @param  array  $input
     * @return Model
     */
    public function create($input)
    {
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }

    /**
     * Update or create model record
     */
    public function updateOrCreate(array $existingColumn, array $input): Model
    {
        return $this->model->updateOrCreate($existingColumn, $input);
    }

     /**
     * Get Articles
     */
    public function get(array $queries=null): Builder
    {
        $query = $this->model->newQuery();
        
        $query->when(!empty($queries['search']), function ($query) use ($queries) {
            $query->where('title', 'like', "%{$queries['search']}%")
            ->orWhere('content', 'like', "%{$queries['search']}%");
        });

        $query->when(! empty($queries['date_from']) && ! empty($queries['date_to']), function ($query) use ($queries) {
            return $query->whereDate('published_at', '>=', $queries['date_from'])
            ->whereDate('published_at', '<=', $queries['date_to']);
        });

        $query->when(!empty($queries['category']), function ($query) use ($queries) {
            return $query->where('category', $queries['category']);
        });

        $query->when(!empty($queries['source']), function ($query) use ($queries) {
            return $query->where('source', $queries['source']);
        });
        
        return $query->latest('published_at');
    }
}