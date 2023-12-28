<?php

namespace Tests\Unit\Service;

use App\Services\SimrsService\SimrsBaseApi;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SimrsBaseApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::fake();

        // Set the configuration for testing
        Config::set('simrs.base_url', 'http://simrs.local/api/');
        Config::set('simrs.access_key', 'apiKey');
    }

    public function test_get_should_return_the_correct_instance()
    {
        $simrsBaseApi = new SimrsBaseApi();
        $response = $simrsBaseApi->get('foo-endpoint', [], [
            'foo' => 'bar',
            'bar' => 'bazz'
        ]);

        Http::assertSent(function ($request) {
            return $request->url() === 'http://simrs.local/api/foo-endpoint?AccessKey=apiKey&foo=bar&bar=bazz';
        });

        $this->assertInstanceOf(\Illuminate\Http\Client\Response::class, $response);
    }

    public function test_post_should_return_the_correct_instance()
    {
        $simrsBaseApi = new SimrsBaseApi();
        $response = $simrsBaseApi->post('foo-endpoint', [], [
            'foo' => 'bar',
            'bar' => 'bazz'
        ]);

        Http::assertSent(function ($request) {
            return $request->url() === 'http://simrs.local/api/foo-endpoint' &&
                        $request['AccessKey'] == 'apiKey' &&
                        $request['foo'] == 'bar' &&
                        $request['bar'] == 'bazz';
        });

        $this->assertInstanceOf(\Illuminate\Http\Client\Response::class, $response);
    }
}
