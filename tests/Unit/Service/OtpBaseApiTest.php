<?php

namespace Tests\Unit\Service;

use App\Services\OtpService\OtpBaseApi;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OtpBaseApiTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake();

        // Set the configuration for testing
        Config::set('watzap.send_message_url', 'http://watzapotp.local/api/');
        Config::set('watzap.api_key', 'apiKey');
        Config::set('watzap.number_key', 'numberKey');
    }

    public function test_get_should_return_the_correct_instance()
    {
        $otpBaseApi = new OtpBaseApi();
        $response = $otpBaseApi->get('foo-endpoint');

        Http::assertSent(function ($request) {
            return $request->url() === 'http://watzapotp.local/api/foo-endpoint?api_key=apiKey&number_key=numberKey'
                && $request['api_key'] === 'apiKey'
                && $request['number_key'] === 'numberKey';
        });


        $this->assertInstanceOf(\Illuminate\Http\Client\Response::class, $response);
    }

    public function test_post_should_return_the_correct_instance()
    {
        $otpBaseApi = new OtpBaseApi();
        $response = $otpBaseApi->post('bar-endpoint', [], ['key' => 'value']);

        Http::assertSent(function ($request) {
            return $request->url() === 'http://watzapotp.local/api/bar-endpoint'
                && $request['api_key'] === 'apiKey'
                && $request['number_key'] === 'numberKey'
                && $request['key'] === 'value';
        });

        $this->assertInstanceOf(\Illuminate\Http\Client\Response::class, $response);
    }
}
