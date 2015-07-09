<?php

use App\Image;
use App\Contracts\Photoable;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Mockery as m;

class ExampleTest extends TestCase
{
    /**
     * Test welcome view
     *
     * @return void
     */
    public function testWelcome()
    {
        $this->visit('/')
             ->seeHeader('Content-Type', 'text/html; charset=UTF-8')
             ->see('Welcome to Middle Earth')
             ->see('Explore the map')
             ->see('See the sights');
    }

    /**
     * Test gallery view
     *
     * @return void
     */
    public function testGallery()
    {
        $count = 30;
        $images = $this->getCollectionOfImages($count);

        $photoService = m::mock(Photoable::class);
        $photoService->shouldReceive('getPhotosByKeyword')
            ->with('middle earth', $count)
            ->andReturn($images);

        $this->app->bind(Photoable::class, function () use ($photoService) {
            return $photoService;
        });

        $this->visit('/gallery')
             ->seeHeader('Content-Type', 'text/html; charset=UTF-8')
             ->see('Middle Earth Photos');

        $imagesOnPage = $this->crawler->filter('img');
        $this->assertEquals($count, count($imagesOnPage));
    }

    /**
     * Test map view
     *
     * @return void
     */
    public function testMap()
    {
        $this->visit('/map')
             ->seeHeader('Content-Type', 'text/html; charset=UTF-8')
             ->see('Middle Earth Map');
    }

    /**
     * Create collection of images for testing
     *
     * @return Collection
     */
    private function getCollectionOfImages($count)
    {
        $collection = new Collection;

        for ($i = 0; $i < $count; $i++) {
            $image = new Image;
            $image->url = uniqid();
            $collection->push($image);
        }

        return $collection;
    }
}
