<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * QB OAuth Endpoint
 * 
 * @author Pavel Espinal
 */
class Qb_oauth_endpoint extends CI_Controller {
    
    /**
     * QB OAuth Endpoint
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
	{
        $this->load->config('quickbooks');

        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'              => $this->config->item('client_id'), // The client ID assigned to you by the provider
            'clientSecret'          => $this->config->item('client_secret'), // The client password assigned to you by the provider
            'redirectUri'           => site_url('qb_oauth_endpoint'),
            'urlAuthorize'          => 'https://appcenter.intuit.com/connect/oauth2',
            'urlAccessToken'        => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'urlResourceOwnerDetails' => $this->config->item('resource_owner_details'),
            'scopes'                => 'com.intuit.quickbooks.accounting',
            'state'                 => 'teststate',
            'response_type'         => 'code'
        ]);

        // If we don't have an authorization code then get one
        if ( ! $this->input->get('code')) 
        {

            // Fetch the authorization URL from the provider; this returns the
            // urlAuthorize option and generates and applies any necessary parameters
            // (e.g. state).
            $lsAuthorizationUrl = $provider->getAuthorizationUrl();

            // Get the state generated for you and store it to the session.
            $this->session->set_userdata('oauth2state', $provider->getState());
            
            // Redirect the user to the authorization URL.
            header('Location: ' . $lsAuthorizationUrl);
            exit;

// Check given state against previously stored one to mitigate CSRF attack
        } 
        else
        {
            if(strcmp($this->session->userdata('oauth2state'), $this->input->get('state')) != 0){
                throw new Exception("The state is not correct from Intuit Server. Consider your app is hacked.");
            }
            
            // Try to get an access token using the authorization code grant.
            $loAccessToken = $provider->getAccessToken('authorization_code', [
                'code' => $this->input->get('code')
            ]);

            // Storing access token in session
            $this->session->set_userdata([
                                    'access_token'      => $loAccessToken->getToken(),
                                    'refresh_token'     => $loAccessToken->getRefreshToken(),
                                    'expiration_date'   => $loAccessToken->getExpires(),
                                    'is_expired'        => $loAccessToken->hasExpired(),
                                    'realmid'           => $this->input->get('realmId')
                                ]);
            
            // Using the access token, we may look up details about the
            // resource owner.
            //try {
            //    $loResourceOwner = $provider->getResourceOwner($loAccessToken);
            //} catch (Exception $ex) {
            //    log_message('error', $ex->getMessage());
            //    exit;
            //}
            //
            // var_export($loResourceOwner->toArray());
        }
        
        print '<script type="text/javascript">
                window.opener.location.href = window.opener.location.href; 
                window.close();
              </script>';
    }
}