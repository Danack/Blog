<?php

use Behat\Mink\Session;

/**
 * @group aws
 */
class AWSTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Auryn\Injector
     */
    private $injector;

    function setup()
    {
        $this->injector = createTestInjector();
    }
    
    function teardown()
    {
    }
    
    function testAWSStorage()
    {
        $storage = $this->injector->make('Intahwebz\Storage\S3Storage');
        $storage->getS3RegionOfBucket('static.basereality.com');
    }


}





//
//$session->visit('http://my_project.dev/second_page.php')// open another page: 
//
//// use history controls:
//$session->reload();
//$session->back();
//$session->forward();
//
//// evaluate JS expression:
//echo $session->evaluateScript(
//             "(function(){ return 'something from browser'; })()"
//);
//
//// wait for n milliseconds or
//// till JS expression becomes true:
//$session->wait(5000,
//    "$('.suggestions-results').children().length > 0"
//);


//
//// setting browser language:
//$session->setRequestHeader('Accept-Language', 'fr');
//
//// retrieving response headers:
//print_r($session->getResponseHeaders());
//
//// set cookie:
//$session->setCookie('cookie name', 'value');
//
//// get cookie:
//echo $session->getCookie('cookie name');
//
//// delete cookie:
//$session->setCookie('cookie name', null);


//
//// init sessions
//$session1 = new \Behat\Mink\Session($driver1);
//$session2 = new \Behat\Mink\Session($driver2);
//
//// start sessions
//$session1->start();
//$session2->start();
//
//$session1->visit('http://my_project.dev/chat.php');
//$session2->visit('http://my_project.dev/chat.php');


//
//$selector = new \Behat\Mink\Selector\NamedSelector();
//$handler  = new \Behat\Mink\Selector\SelectorsHandler(array(
//    'named' => $selector
//));
//
//// XPath query to find the fieldset:
//$xpath1 = $selector->translateToXPath(
//                   array('fieldset', 'id|legend')
//);
//$xpath1 = $handler->selectorToXpath('named',
//    array('fieldset', 'id|legend')
//);
//
//// XPath query to find the field:
//$xpath2 = $selector->translateToXPath(
//                   array('field', 'id|name|value|label')
//);
//$xpath2 = $handler->selectorToXpath('named',
//    array('field', 'id|name|value|label')
//);


//$el = $page->find('css', '.something');
//
//// get tag name:
//echo $el->getTagName();
//
//// check that element has href attribute:
//$el->hasAttribute('href');
//
//// get element's href attribute:
//echo $el->getAttribute('href');


//
//$el1 = $page->find(...);
//$el2 = $page->find(...);
//
//$el1->dragTo($el2);



//// check/uncheck checkbox:
//if ($el->isChecked()) {
//    $el->uncheck();
//}
//$el->check();
//
//// select option in select:
//$el->selectOption('optin value');
//
//// attach file to file input:
//$el->attachFile('/path/to/file');
//
//// get input value:
//echo $el->getValue();
//
//// set intput value:
//$el->setValue('some val');
//
//// press the button:
//$el->press();