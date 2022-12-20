<?php 

class WebServiceAPI{    
    private $url;
    private $username;
    private $user_accessKey;
    private $challenge_token = "";
    private $login_data;
    private $modulesId = array(
        'Campaigns' => '1',
        'Vendors' => '2',
        'Faq' => '3',
        'Quotes' => '4',
        'PurchaseOrder' => '5',
        'SalesOrder' => '6',
        'Invoice' => '7',
        'PriceBooks' => '8',
        'Calendar' => '9',
        'Leads' => '10',
        'Accounts' => '11',
        'Contacts' => '12',
        'Potentials' => '13',
        'Products' => '14',
        'Documents' => '15',
        'Emails' => '16',            
        'HelpDesk' => '17',
        'Events' => '18',
        'Users' => '19',
        'Groups' => '20',
        'Currency' => '21',
        'DocumentFolders' => '22',
        'CompanyDetails' => '23',          
        'PBXManager' => '24',
        'ServiceContracts' => '25',
        'Services' => '26',
        'Assets' => '27',
        'ModComments' => '28',
        'ProjectMilestone' => '29',
        'ProjectTask' => '30',
        'Project' => '31',
        'SMSNotifier' => '32',
        'LineItem' => '33',
        'Tax' => '34',
        'ProductTaxes' => '35'
 );


    /** 
     * Creates a new object that's able to connect to the webservice api
     * @param string $url e.g: https://your-vtiger-domain.com/webservice.php'
     * @param string $username 
     * @param string $user_accessKey 
    */
    
    public function __construct($url, $username, $user_accessKey){
        $this->url = $url;
        $this->username = $username;
        $this->user_accessKey = $user_accessKey;
        $this->challenge_token = $this->getChallengeToken();
        $this->LoginUser();

        $this->modulesId = $this->getCurrentModules()['result'];
    }

    public function getModulesId(){
        return $this->modulesId;
    }
    
    /** 
        * @param string $url e.g: https://your-vtiger-domain.com/webservice.php'
        * @param string $username 
        * @param string $user_accessKey 
        * @return array
        * -------------------
        * Login using username and user token
        * Returns an array containing:
        * * Session name
        * * Session id
        * * Challenge token..etc
    */
    public function LoginUser(){
        // Set up the request data
        $data = array(
            'operation' => 'login',
            'username' => $this->username,
            'accessKey' => md5($this->challenge_token .''. $this->user_accessKey) 
        );

        $this->login_data = $this->post_curl($data)['result'];
        return $this->login_data;
    }

    /**
     * Get the challenge token of the selected user using username
     */
    public function getChallengeToken(){
        $data = array(
            'operation' => 'getchallenge',
            'username' => $this->username
        );
        return $this->post_curl($data)['result']['token'];
    }

    /**
     * @param $type string Record type
     * @param $elementsArray array Array of the new record elements/fields
     */

    public function createRecord($type, $elementsArray){
        $data = array(
            'operation' => 'create',
            'sessionName' => $this->login_data['sessionName'],
            'elementType' => $type,
            'element' => json_encode($elementsArray)
        );

        return $this->post_curl($data);
    }

    public function retrieve($module, $id){
        $data = array(
            'operation' => 'retrieve',
            'sessionName' => $this->login_data['sessionName'],
            'id'=>$this -> $this->getSingleModuleId($module) . 'x' . $id
        );
        return $this->post_curl($data);
    }

    public function getLoginData(){
        return $this->login_data;
    }

    private function getCurrentModules(){
        $data = array(
            'operation' => 'listtypes',
            'sessionName' => $this->login_data['sessionName']
        );

        return $this->post_curl($data);
    }

    private function getSingleModuleId($module){
        return array_search($module,$this->modulesId['types'])+1;
    }



    /**
     * Takes one array of request array & returns the curl response
     * @param array $data
     */
    private function post_curl($data){
        // Set cURL options
        $options = array(
            CURLOPT_URL => $this->url .'?'.http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => http_build_query($data),
        );
        
        // Set up the cURL request
        $ch = curl_init();
        curl_setopt_array($ch, $options);

        // Send the request and get the response
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $response;

    }

}

?>