<?php

namespace App\Interfaces;

interface NewsSourceInterface
{
    public function fetchArticles(): array;

    public function transformArticles(array $articles): array;
}