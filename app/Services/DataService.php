<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use JsonException;
use stdClass;

class DataService
{
    /**
     * @throws JsonException
     */
    public function getDataFromUrl(string $url): stdClass
    {
        $response = null;
        $client = new Client();
        try {
            $response = $client->get($url);
        } catch (GuzzleException $e) {
            Log::error('GuzzleRequest Failed: ' . $e->getMessage());
        }

        $cacheIdentifier = sha1($url);
        if ($response !== null) {
            if (Cache::has($cacheIdentifier . '-etag')
                && $response->getHeader('ETag')[0] === Cache::get($cacheIdentifier . '-etag')
            ) {
                return Cache::get($cacheIdentifier);
            }

            Cache::put($cacheIdentifier, json_decode((string) $response->getBody(), false, 512, JSON_THROW_ON_ERROR));
            Cache::put($cacheIdentifier . '-etag', $response->getHeader('ETag')[0]);
        }


        return Cache::get($cacheIdentifier);
    }
}
