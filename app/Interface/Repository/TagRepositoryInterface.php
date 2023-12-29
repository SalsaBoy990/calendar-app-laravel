<?php

namespace App\Interface\Repository;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TagRepositoryInterface
{

    /**
     * @return LengthAwarePaginator
     */
    public function getPaginatedTags(): LengthAwarePaginator;


    /**
     * @return Collection
     */
    public function getTags(): Collection;


    /**
     * @param  array  $data
     * @return Tag
     */
    public function createTag(array $data): Tag;


    /**
     * @param  Tag  $tag
     * @param  array  $data
     * @return bool|null
     */
    public function updateTag(Tag $tag, array $data): bool|null;


    /**
     * @param  Tag  $tag
     * @return bool|null
     */
    public function deleteTag(Tag $tag): bool|null;
}
