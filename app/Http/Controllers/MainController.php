<?php namespace App\Http\Controllers;

use App\Contracts\Mappable;
use App\Contracts\Photoable;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function __construct(Photoable $photo, Mappable $map)
    {
        $this->photo = $photo;
        $this->map = $map;
    }

    public function welcome()
    {
        return view('welcome');
    }

    public function gallery()
    {
        $photos = $this->photo->getPhotosByKeyword('middle earth', 30);

        return view('gallery', ['photos' => $photos]);
    }

    public function map()
    {
        return view('map');
    }

    public function mapTile($source, Request $request)
    {
        return $this->map->createTile($source,$request);
    }
}
