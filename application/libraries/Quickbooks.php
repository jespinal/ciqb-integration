<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(APPPATH) . '/vendor/quickbooks/v3-php-sdk/src/config.php');

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;

/**
 * @author Pavel Espinal
 */
class Quickbooks {
    
    /**
     *
     * @var CI_Controller
     */
    private $CI;
    
    /**
     * @var DataService
     */
    private $dataService;
    
    
    public function __construct() {
        
        $this->CI =& get_instance();
        
        if ( ! $this->CI->session instanceof CI_Session)
        {
            throw new RuntimeException("Attempting to load Quickbooks library in absence of CI_Session.");
        }
        
        $this->CI->config->load('quickbooks');

        $this->setDataService();
    }

    /**
     * Set Data Service
     */
    private function setDataService() {
        
        $lbReturn = false;
        
        try {
            $this->dataService = DataService::Configure(array(
                'auth_mode'         => 'oauth2',
                'ClientID'          => $this->CI->config->item('client_id'),
                'ClientSecret'      => $this->CI->config->item('client_secret'),
                'accessTokenKey'    =>  $this->CI->session->userdata('access_token'),
                'refreshTokenKey'   => $this->CI->session->userdata('refresh_token'),
                'QBORealmID'        => $this->CI->session->userdata('realmid'),
                'baseUrl'           => (ENVIRONMENT == 'production') ? ENVIRONMENT : 'development'
            ));
            
            $lbReturn = true;
            
        } catch (Exception $ex) {
            log_message('error', "There was a problem while initializing DataService.\n" . $ex->getMessage());
        }
        
        return $lbReturn;
    }
    
    /**
     * Get Data Service
     * 
     * @return QuickBooksOnline\API\DataService\DataService
     * @throws Exception
     */
    public function getDataService() {
        if ( ! $this->dataService instanceof DataService) {
            throw new Exception("The DataService object of the Quickbooks SDK is not ready.");
        } else {
            return $this->dataService;
        }
    }
}