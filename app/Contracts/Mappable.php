<?php namespace App\Contracts;

use Illuminate\Http\Request;

interface Mappable
{
    public function createTile($source, Request $request);
}
