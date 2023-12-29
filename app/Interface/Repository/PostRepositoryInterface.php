<?php

namespace App\Interface\Repository;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{

    /**
     * @param  array  $data
     * @return Post
     */
    public function createPost(array $data): Post;

    /**
     * @param  string  $slug
     * @return Post
     */
    public function getPostBySlug(string $slug): Post;


    /**
     * @param  Post  $post
     * @return bool
     */
    public function deletePost(Post $post): bool;


    /**
     * @param  Post  $post
     * @param  array  $data
     * @return mixed
     */
    public function updatePost(Post $post, array $data): mixed;


    /**
     * @return Collection
     */
    public function getPosts(): Collection;


    /**
     * @return LengthAwarePaginator
     */
    public function getPaginatedPosts(): LengthAwarePaginator;


    /**
     * @return LengthAwarePaginator
     */
    public function getPaginatedPublishedPosts(): LengthAwarePaginator;


}

