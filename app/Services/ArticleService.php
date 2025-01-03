<?php

namespace App\Services;

use App\Http\Resources\ArticleResource;
use App\Repository\ArticleRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ArticleService
{
    /**
     * Article Service constructor.
     */
    public function __construct(
        private readonly ArticleRepository $repository
    )
    {
        
    }

    public function create()
    {

    }
    
    public function updateOrCreate(array $existingColumn, array $data):array|Model
    {
        return $this->repository->updateOrCreate($existingColumn, $data);
    }

    public function find(array $queries): array
    {
        $builder = $this->repository->get($queries);

        return $this->paginate($builder, function (Model $article) {
            return new ArticleResource($article);
        }, $queries['page_number'], config('constants.pagination_limit'));
    }

    /**
     * Build Paginated Records of articles using transformed data
     */
    public function paginate(Builder $builder, callable $callback, int $page=null, int $perPage=50): array
    {
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
        $paginator = $builder->paginate($perPage, ['*'], 'page', $page);
        
        return [
            'data' => $paginator->getCollection()->transform($callback),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'next_page' => $paginator->hasMorePages() ? $paginator->currentPage() + 1 : null,
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total()
            ]
        ];
 
    }
}