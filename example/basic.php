<?php

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once dirname(__FILE__).'/shared/helpers.php';
require_once dirname(__FILE__).'/shared/config.php';

use CloudCompli\WebApiClient\V2\OAuth2Provider;
use CloudCompli\WebApiClient\V2\OAuth2Session;

Output::init();

$provider = new OAuth2Provider([
    'clientId'                => $clientId,
    'clientSecret'            => $clientSecret,
    'redirectUri'             => $scriptUrl,
    'url'                     => $cloudcompliUrl
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
    $authorizationUrl = $provider->getAuthorizationUrl();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

} else {

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);
        
        $session = new OAuth2Session($provider, $accessToken);
        
        echo new View('request-response', [
            'title' => 'Get User',
            'request' => $session->rawGetRequest('user'),
            'response' => $session->get('user')
        ]);
        
        echo new View('request-response', [
            'title' => 'Get Company',
            'request' => $session->rawGetRequest('company'),
            'response' => $session->get('company')
        ]);

        $projects = $session->get('projects');
        echo new View('request-response', [
            'title' => 'Get Projects',
            'request' => $session->rawGetRequest('projects'),
            'response' => $projects
        ]);
        
        $projectsData = json_decode($projects->getBody(), true);
        $project = $projectsData[0];
        
        $classification = $session->get('classifications/'.$project['classification_id']);
        echo new View('request-response', [
            'title' => 'Get Classification',
            'request' => $session->rawGetRequest('classifications/'.$project['classification_id']),
            'response' => $classification
        ]);
        
        $classificationData = json_decode($classification->getBody(), true);
        $form = $classificationData['forms'][0];
        
        echo new View('request-response', [
            'title' => 'Get Form Content',
            'request' => $session->rawGetRequest('forms/'.$form['name']),
            'response' => $session->get('forms/'.$form['name'])
        ]);
        
        echo new View('request-response', [
            'title' => 'Get Form Pre-population Data for Project',
            'request' => $session->rawGetRequest('projects/'.$project['id'].'/forms/'.$form['name'].'/prepopulate-data'),
            'response' => $session->get('projects/'.$project['id'].'/forms/'.$form['name'].'/prepopulate-data')
        ]);

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        echo $e->getMessage();

    }

}
        
Output::render();