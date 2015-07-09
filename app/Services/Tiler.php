<?php namespace App\Services;

use Exception;
use App\Contracts\Mappable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as ResponseFactory;
use League\Glide\ServerFactory;

class Tiler implements Mappable
{
    /**
     * Create service
     */
    public function __construct()
    {
        $this->mapPath = base_path().'/resources/assets/maps/source/';
        $this->cachePath = base_path().'/resources/assets/maps/cache/';

        $this->server = ServerFactory::create([
            'source' => $this->mapPath,
            'cache' => $this->cachePath,
        ]);
    }

    /**
     * Create tile
     *
     * @param  string  $source
     * @param  integer $zoom
     * @param  integer $x
     * @param  integer $y
     *
     * @return Response
     */
    public function createTile($source, Request $request)
    {
        try {
            $config = $this->getConfig(
                $source,
                $request->input('zoom'),
                $request->input('x'),
                $request->input('y')
            );

            return $this->server->outputImage($source, $config);
        } catch (Exception $e) {
            abort(404);
        }
    }

    /**
     * Create image manipulation configuration
     *
     * @param  integer $zoom
     * @param  integer $x
     * @param  integer $y
     *
     * @return array
     */
    protected function getConfig($source, $zoom, $x, $y)
    {
        $tileWidth = 500;
        $tileHeight = 500;

        $config = ['w' => $tileWidth, 'h' => $tileHeight];

        if (empty($zoom)) {
            return $config;
        }

        $multiple = $this->createMultiple($zoom, 2);

        list($width, $height) = getimagesize($this->mapPath.$source);

        $sectionWidth = floor($width / $multiple);
        $sectionHeight = floor($height / $multiple);

        $xPosition = $sectionWidth * $x;
        $yPosition = $height - $sectionHeight * ($y + 1);

        $rect = [];
        $rect[] = $sectionWidth;
        $rect[] = $sectionHeight;
        $rect[] = $xPosition;
        $rect[] = $yPosition;

        $config['rect'] = implode(',', $rect);

        return $config;
    }

    /**
     * Create multiplier from count and seed
     *
     * @param  integer $count
     * @param  integer $seed
     *
     * @return integer
     */
    private function createMultiple($count, $seed)
    {
        $value = 1;

        for ($i = 0; $i < $count; $i++) {
            $value = $value * $seed;
        }

        return $value;
    }
}
