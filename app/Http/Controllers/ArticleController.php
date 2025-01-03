<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use App\Support\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(private readonly ArticleService $service)
    {
        
    }

    public function index(Request $request)
    {
        $data = [
            'page_number' => $request->integer('page_number', 1),
            'source' => $request->query('source'),
            'search' => $request->query('search'),
            'category' => $request->query('category'),
            'date_from' => Carbon::parse($request->query('date_from'))->format('Y-m-d'),
            'date_to' => Carbon::parse($request->query('date_to'))->format('Y-m-d'),
        ];

        $articles = $this->service->find($data);

        return (new ApiResponse())->paginate('Success retrieving articles', $articles);

    }
}
