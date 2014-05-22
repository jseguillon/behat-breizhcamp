<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;
use Sanpi\Behatch\Context\BehatchContext;
//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->useContext('behatch', new BehatchContext($parameters));
    }

    /**
     * @Given /^I tap on "([^"]*)"$/
     */
    public function iTapOn($button)
    {
        $elem=$this->curlFindElement("/elements", array("using" => "name", "value" => "$button"));

        $this->curlActionOnElement("/click", $elem->value[0]->ELEMENT); 
    

    }

    public function curlFindElement($url, $array){

       $data_string = json_encode($array);                                                                                   
 
$ch = curl_init($this->getSession()->getDriver()->getWebDriverSession()->getUrl() . $url);                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);                                                                                                                   

$result = curl_exec($ch);
curl_close ($ch);

    return json_decode($result);
 
    }

    public function curlActionOnElement($url, $element){
$ch = curl_init($this->getSession()->getDriver()->getWebDriverSession()->getUrl() . "/element/" .$element . $url);                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                                                                                     
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json')                                                                    
);                                                                                                                   

$result = curl_exec($ch);
curl_close ($ch);
//echo print_r($result, true);

return json_decode($result);

    }
}
