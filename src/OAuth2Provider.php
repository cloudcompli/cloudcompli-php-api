<?php

namespace CloudCompli\WebApiClient\V2;

use League\OAuth2\Client\Provider\GenericProvider;
use Psr\Http\Message\RequestInterface;

class OAuth2Provider extends GenericProvider
{
    protected $apiUrl;
    
    public function __construct(array $options = array(), array $collaborators = array())
    {
        if(!array_key_exists('url', $options)){
            throw new InvalidArgumentException('Required options not defined: url');
        }
        
        $this->apiUrl = $options['url'].'/api/v2';
        
        if(!array_key_exists('urlAuthorize', $options))
            $options['urlAuthorize'] = $options['url'].'/oauth2/authorize';
        
        if(!array_key_exists('urlAccessToken', $options))
            $options['urlAccessToken'] = $options['url'].'/oauth2/token';
        
        if(!array_key_exists('urlResourceOwnerDetails', $options))
            $options['urlResourceOwnerDetails'] = null;
        
        parent::__construct($options, $collaborators);
    }
    
    public function getBaseApiUrl()
    {
        return $this->apiUrl;
    }
    
    public function getResponseObject(RequestInterface $request)
    {
        $response = $this->sendRequest($request);
        $parsed = $this->parseResponse($response);

        $this->checkResponse($response, $parsed);

        return $response;
    }
}