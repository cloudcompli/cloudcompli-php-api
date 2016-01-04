<?php

namespace CloudCompli\WebApiClient\V2;

use CloudCompli\WebApiClient\V2\OAuth2Provider;
use League\OAuth2\Client\Token\AccessToken;

class OAuth2Session
{
    protected $provider;
    protected $accessToken;
    
    public function __construct(OAuth2Provider $provider, AccessToken $accessToken)
    {
        $this->provider = $provider;
        $this->accessToken = $accessToken;
    }
    
    public function makeUrl($path)
    {
        if(substr($path, 0, 1) != '/')
            $path = '/'.$path;
        
        return $this->provider->getBaseApiUrl().$path;
    }
    
    public function getRequest($path)
    {
        $request = $this->provider->getAuthenticatedRequest(
            'GET',
            $this->makeUrl($path),
            $this->accessToken,
            [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'verify' => false
            ]
        );
        
        return $request;
    }
    
    public function postRequest($path, $data)
    {
        $request = $this->provider->getAuthenticatedRequest(
            'POST',
            $this->makeUrl($path),
            $this->accessToken,
            [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($data),
                'verify' => false
            ]
        );
        
        return $request;
    }
    
    public function rawGetRequest($path)
    {
        $url = $this->makeUrl($path);
        
        $request = $this->getRequest($path);
        
        $lines = [
            'GET '.parse_url($url, PHP_URL_PATH).(parse_url($url, PHP_URL_QUERY) ? ('?'.parse_url($url, PHP_URL_QUERY)) : '').' HTTP/'.$request->getProtocolVersion()
        ];
        
        foreach($request->getHeaders() as $headerName => $headerValues)
            foreach($headerValues as $headerValue)
                $lines[] = $headerName.': '.$headerValue;
        
        return implode(PHP_EOL, $lines);
    }
    
    public function rawPostRequest($path, $data)
    {
        $url = $this->makeUrl($path);
        
        $request = $this->getRequest($path);
        
        $lines = [
            'POST '.parse_url($url, PHP_URL_PATH).(parse_url($url, PHP_URL_QUERY) ? ('?'.parse_url($url, PHP_URL_QUERY)) : '').' HTTP/'.$request->getProtocolVersion()
        ];
        
        foreach($request->getHeaders() as $headerName => $headerValues)
            foreach($headerValues as $headerValue)
                $lines[] = $headerName.': '.$headerValue;
        
        $lines[] = '';
        
        $lines[] = json_encode($data);
        
        return implode(PHP_EOL, $lines);
    }
    
    public function get($path)
    {
        return $this->provider->getResponseObject($this->getRequest($path));
    }
    
    public function post($path, $data)
    {
        return $this->provider->getResponseObject($this->postRequest($path, $data));
    }
}