<?php

namespace App\Interface\Services;

interface ImageServiceInterface
{
    public function getImageAbsolutePath(string $imageUrl): string;
}
