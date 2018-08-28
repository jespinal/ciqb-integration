<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * QB OAuth Endpoint
 * 
 * Quickbooks Online OAuth2 authorization endpoint.
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
    
    /**
     * QB Oauth Endpoint start
     * 
     * @throws Exception
     */
    public function index()
	{
        // Note: this controller is likely to be accessed through a popup window,
        // so I tried to keep it simple. Once the authorization is copleted, you'll
        // have the access token in session, hence, you can redirect to another controller
        // where you want to do some magic :)
        
        /* @var $loProvider \League\OAuth2\Client\Provider\GenericProvider */
        $loProvider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'              => $this->config->item('client_id'),        // Your Intuit App's details provides you this info
            'clientSecret'          => $this->config->item('client_secret'),    // Same as above
            'redirectUri'           => site_url('qb_oauth_endpoint'),
            'urlAuthorize'          => 'https://appcenter.intuit.com/connect/oauth2',
            'urlAccessToken'        => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'urlResourceOwnerDetails' => $this->config->item('resource_owner_details'),
            'scopes'                => 'com.intuit.quickbooks.accounting',      // Consider using incremental authorization, this is just an example
                                                                                // https://developer.intuit.com/docs/0100_quickbooks_online/0100_essentials/000500_authentication_and_authorization/connect_from_within_your_app#.EF.BB.BFIncremental_authorization
            'state'                 => get_secure_hash(),
            'response_type'         => 'code'
        ]);

        // If we don't have an authorization code then get one
        if ( ! $this->input->get('code')) 
        {

            // Fetch the authorization URL from the provider; this returns the
            // urlAuthorize option and generates and applies any necessary parameters
            // (e.g. state).
            $lsAuthorizationUrl = $loProvider->getAuthorizationUrl();

            // Get the state generated for you and store it to the session.
            $this->session->set_userdata('oauth2state', $loProvider->getState());

            // Redirect the user to the authorization URL.
            header('Location: ' . $lsAuthorizationUrl);
            exit;
        } 
        else
        {
            // Check given state against previously stored one to mitigate CSRF attack
            if(strcmp($this->session->userdata('oauth2state'), $this->input->get('state')) != 0){
                throw new Exception("The state is not correct from Intuit Server. Consider your app is hacked.");
            }
            
            // Try to get an access token using the authorization code grant.
            try {
                $loAccessToken = $loProvider->getAccessToken('authorization_code', [
                    'code' => $this->input->get('code')
                ]);
            } catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $ex) {
                log_message('error', $ex->getMessage());
                
                if (in_array(ENVIRONMENT, ['development','testing']))
                {
                    // Something bad happened...
                    print $ex->getMessage();
                }
                
                exit;
            }

            // Storing access token and some other important data in session.
            // Pay attention to each value, as we will them later and it can get
            // confuse if you don't know where they came from later.
            // 
            // For more elaborated approaches you might want to save these into 
            // the DB and then have a Cron job validating the token periodically
            // (E.g. using the expiration date, etc.)
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
            //    $loResourceOwner = $loProvider->getResourceOwner($loAccessToken);
            //} catch (Exception $ex) {
            //    log_message('error', $ex->getMessage());
            //    exit;
            //}
            //
            // var_export($loResourceOwner->toArray());
        }
        
        // Closing popup and refreshing parent window
        print '<script type="text/javascript">
                window.opener.location.href = window.opener.location.href; 
                window.close();
              </script>';
    }
}