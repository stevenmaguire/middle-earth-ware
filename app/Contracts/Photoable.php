<?php namespace App\Contracts;

interface Photoable
{
    public function getPhotosByKeyword($keyword, $count = 10);
}
