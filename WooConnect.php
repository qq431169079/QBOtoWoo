<?php
/*      WooConnect.php
*       This wil provides functions to connect and
*       interact with the WooCommerce API
*/
// Load Composer build
require __DIR__ . '/vendor/autoload.php';
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
class WooConnect{
    

    /* DEVELOPMENT ONLY
    *  Parse our ini file created by install
    *  For production I need to refactor this to the Worpress settings in db
    */
    
    public $settings = array();
    public $api_conn;

    public function loadSettings(){
        $settingsFile = 'file.ini';
        try {
            $this->settings = parse_ini_file($settingsFile);
            return true;
            // var_dump($this->settings);
        }
        catch (Exception $e) {
            die($e->getMessage());
        }
    }

    
    // Call storeConnect to open a new connection to the API
    public function storeConnect() { 
        try{
            $woocommerce = new Client(
            $this->settings['woo-url'], 
            $this->settings['ck'], 
            $this->settings['cs'],
            [
                'wp_api' => true,
                'version' => 'wc/v2',
            ]
            );
            $this->api_conn = $woocommerce;
            echo 'Success?';
            // var_dump($this->api_conn);
        }
        catch(ClientException $e){
            $e->getMessage(); // Error message.
        $e->getRequest(); // Last request data.
        $e->getResponse(); // Last response data.
        $err_msg = htmlspecialchars('Could not connect to the WooCommerce API'.
                   '<br>The URL provided in settings is: ' . $settings['woo-url'] .
                   '<br>Your Consumer Key: ' . $settings['ck'] .
                   '<br>Your Consumer Secret: ' .$settings['cs']);
        echo $err_msg;
        }
    }
    
    // Now that we have a client lets make some requests
    public function getFromWoo($query){

        try {
        // Array of response results.
        /* To pull all products call getFromWoo('products')
        *  get() from wc-api takes standard json requests
        *
        */
            
        $results = $this->api_conn->get($query);
        // Example: ['customers' => [[ 'id' => 8, 'created_at' => '2015-05-06T17:43:51Z', 'email' => ...

        // Last request data.
        $lastRequest = $this->api_conn->http->getRequest();
        $lastRequest->getUrl(); // Requested URL (string).
        $lastRequest->getMethod(); // Request method (string).
        $lastRequest->getParameters(); // Request parameters (array).
        $lastRequest->getHeaders(); // Request headers (array).
        // var_dump($lastRequest); // Request body (JSON).

        // Last response data.
        $lastResponse = $this->api_conn->http->getResponse();
        $lastResponse->getCode(); // Response code (int).
        $lastResponse->getHeaders(); // Response headers (array).
        $resObj = $lastResponse->getBody(); // Response body (JSON).
        
        return $resObj;    
        /* echo 'Product Array as JSON';
        *  echo $productArray;
        echo 'product array as php ';
        $obj = json_decode($productArray);
        var_dump($obj);
        echo '<br>';


        echo $obj[0]->id;
        echo $obj[0]->sku;
        echo $obj[0]->name;
        echo $obj[0]->price;
            
            
            
        // These do the same thing    
        foreach($obj as $product){
            echo $product->id;
        }
        for($i = 0; $i < sizeof($obj); $i++){
            echo $obj[$i]->id;
        }
        */

    } catch (HttpClientException $e) {
        $e->getMessage(); // Error message.
        $e->getRequest(); // Last request data.
        $e->getResponse(); // Last response data.
        $err_msg = 'Nothing yet.  Lets debug WooCommerce.';
        echo $err_msg;
    }


    }
 
    
} // End of WooConnect class






$connection = new WooConnect();

$connection->loadSettings();
$connection->storeConnect();
// print_r($connection->getFromWoo('products/attributes'));
$JSONres = $connection->getFromWoo('products/8');
$productKeys = array();
// var_dump($JSONres);
$rescueArr = json_decode($JSONres);
$quickArray = array();


foreach ($rescueArr as $key => $value) {
       
       if(is_object($value) || is_array($value)){
           if(is_object($value)){
               echo 'Object Name:' . $key . ' =  <br>';
               array_push($productKeys, $key);
               echo 'Pushed to Array';
               foreach($value as $objKey => $attr){
                   echo 'Object Attribute: ' . $objKey . '<br>' . $attr;
               }
               var_dump($value);
           }
           else{
               // Then its an array
               echo '<p>Array Name:' . $key;
               array_push($productKeys, $key);
               echo '<b>Pushed to Array</b></p>';
               print_r($value);
              
                   // to break down the Array
                   foreach($value as $subkey => $subval){
                       if(is_object($subval)){
                            echo 'This subcalue is an Object';
                            echo $subkey;
                       }
                       /*
                       echo '<p>Subkey</p>';
                       print_r($subkey);   
                       echo '<p>SubVal</p>';    
                       print_r($subval);      
                       */
                       
                    }
                    
                }
                    
               
           }
               // array_push($quickArray, $value);
           
           // var_dump($quickArray);
            
       
       else {
           echo '<p>' . $key . ' = ' . $value;
           array_push($productKeys, $key);
           echo ' <b>*Pushed to Array</b></p>';
       }
            
    }
// var_dump($rescueArr);
echo '<h1>Product Attributes</h1>';
print_r($productKeys);

?>