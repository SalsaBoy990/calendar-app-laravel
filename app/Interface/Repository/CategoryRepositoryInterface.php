<?php

namespace App\Interface\Repository;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{

    /**
     * @return Collection
     */
    public function getRootCategories(): Collection;


    /**
     * @param  int  $parentCategoryId
     * @return Category
     */
    public function getCategoryById(int $parentCategoryId): Category;


    /**
     * @param  array  $data
     * @return Category
     */
    public function createCategory(array $data): Category;


    /**
     * @param  Category  $category
     * @param  array  $data
     * @return bool|null
     */
    public function updateCategory(Category $category, array $data): bool|null;


    /**
     * @param  Category  $category
     * @return bool|null
     */
    public function deleteTag(Category $category): bool|null;
}
