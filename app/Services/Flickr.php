<?php namespace App\Services;

use App\Image;
use App\Contracts\Photoable;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Collection;

class Flickr implements Photoable
{
    /**
     * Api key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Create service
     */
    public function __construct()
    {
        $this->client = new HttpClient();
        $this->apiKey = config('services.flickr.key');
    }

    /**
     * Get photos by searching with given keyword
     *
     * @param  string  $keyword
     *
     * @return [type]  [description]
     *
     * https://api.flickr.com/services/rest/
     *     ?method=flickr.photos.search
     *     &api_key=0be06ecdf3fa1ac784e8fd10c279790c
     *     &text=middle+earth
     *     &format=json
     *     &nojsoncallback=1
     */
    public function getPhotosByKeyword($keyword, $count = 10)
    {
        $result = json_decode(
            $this->client->get(
                'https://api.flickr.com/services/rest/',
                ['query' =>  [
                    'method' => 'flickr.photos.search',
                    'format' => 'json',
                    'nojsoncallback' => '1',
                    'sort' => 'interestingness-desc',
                    'per_page' => $count,
                    'page' => rand(1,100),
                    'api_key' => $this->apiKey,
                    'text' => $keyword,
                ]]
            )->getBody()
        );

        return $this->createCollection($result);
    }

    /**
     * Create collection from payload
     *
     * @param  object $payload
     *
     * @return Collection
     */
    protected function createCollection($payload)
    {
        $collection = new Collection;

        array_map(function ($photo) use (&$collection) {
            $collection->push($this->createImage($photo));
        }, $payload->photos->photo);

        return $collection;
    }

    /**
     * Convert payload into Image
     *
     * @param  object $payload
     *
     * @return Image
     */
    protected function createImage($payload)
    {
        $image = new Image;
        $image->url = 'https://farm'.$payload->farm.'.staticflickr.com/'.$payload->server.'/'.$payload->id.'_'.$payload->secret.'.jpg';

        return $image;
    }
}
