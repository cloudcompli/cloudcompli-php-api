<?php

define('INCLUDE_FORM_SUBMISSION', false);

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
            'code' => $_GET['code'],
            'verify' => false
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
        
        $formEntryResponse = $session->post('projects/'.$project['id'].'/forms/'.$form['name'], [
            'test_field' => 'aaa'
        ]);
        
        echo new View('request-response', [
            'title' => 'Post Form Data for Project',
            'request' => $session->rawPostRequest('projects/'.$project['id'].'/forms/'.$form['name'], [
                'test_field' => 'aaa'
            ]),
            'response' => $formEntryResponse
        ]);
        
        if(INCLUDE_FORM_SUBMISSION){
        
            $formEntry = json_decode($formEntryResponse->getBody(), true);

            $imageData = 'data:image/png;base64,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';

            $formPhotoPostResponse = $session->post('projects/'.$project['id'].'/forms/'.$form['name'].'/photo/'.$formEntry['_id'], [
                'photo' => $imageData,
                'mime_type' => 'image/png',
                'description' => 'Some description'
            ]);

            echo new View('request-response', [
                'title' => 'Post Form Data for Project',
                'request' => $session->rawPostRequest('projects/'.$project['id'].'/forms/'.$form['name'].'/photo/'.$formEntry['_id'], [
                    'photo' => $imageData,
                    'mime_type' => 'image/png',
                    'description' => 'Some description'
                ]),
                'response' => $formPhotoPostResponse
            ]);

            $formPhotoEntry = json_decode($formPhotoPostResponse->getBody(), true);
            echo '<img src="data:image/png;base64,'.$formPhotoEntry['data'].'">';
        
        }

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        echo $e->getMessage();

    }

}
        
Output::render();