<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/9/25
 * Time: 下午5:39
 */

namespace Providers\RequestClient;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;


trait HasHttpRequest
{

    protected function get($endpoint, $query = [], $headers = [])
    {
        return $this->request('get', $endpoint, [
            'headers' => $headers,
            'query'   => array_merge($query, $this->querys()),
        ]);
    }

    protected function post($endpoint, $data, $options = [])
    {
        $endpoint = $this->query($endpoint);

        if (!is_array($data)) {
            $options['body'] = $data;
        } else {
            $options['form_params'] = $data;
        }

        return $this->request('post', $endpoint, $options);
    }

    protected function request($method, $endpoint, $options = [])
    {
        $options = array_merge($options, $this->options());
        return $this->unwrapResponse($this->getHttpClient($this->getBaseOptions())->{$method}($endpoint, $options));
    }

    protected function getBaseOptions()
    {
        $options = [
            'base_uri' => $this->getBaseUri(),
            'timeout'  => $this->getTimeout() ?: 3,
        ];

        return $options;
    }

    private function query($endpoint)
    {
        $request = new Collection(parse_url($endpoint));

        $query = array_merge($request->get('query', []), $this->querys());

        if (!$query) return $endpoint;

        return $request->get('path') . '?' . http_build_query($query);
    }

    protected function getHttpClient(array $options = [])
    {
        return new Client($options);
    }

    protected function unwrapResponse(ResponseInterface $response)
    {
        $contentType = $response->getHeaderLine('Content-Type');
        $contents    = $response->getBody()->getContents();

        if (false !== stripos($contentType, 'json') || stripos($contentType, 'javascript')) {
            return json_decode($contents, true);
        } elseif (false !== stripos($contentType, 'xml')) {
            return json_decode(json_encode(simplexml_load_string($contents)), true);
        }

        return $contents;
    }

    abstract function getBaseUri();

    abstract function getTimeout();

    abstract function options();

    abstract function querys();

}