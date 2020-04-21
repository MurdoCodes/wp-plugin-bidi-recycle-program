<?php
/**
* @package Bidi Recycle Program
*/
namespace Includes\AuthorizeNet_API;
require plugin_dir_path( dirname( __FILE__, 2 ) ) . 'vendor/autoload.php';
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
define("API_LOGIN_ID", '7E5Mu38fGrx4');
define("TRANSACTION_KEY", '7UDc2e5CdL4Fe95D');

class AuthorizeNetService {
	
	private $APILoginId;
    private $APIKey;
    private $refId;
    private $merchantAuthentication;
    public $responseText;   
    
    public function __construct(){
        $this->APILoginId = API_LOGIN_ID;
        $this->APIKey = TRANSACTION_KEY;
        $this->refId = 'ref' . time();
        $this->merchantAuthentication = $this->setMerchantAuthentication();
        $this->responseText = array("1"=>"Approved", "2"=>"Declined", "3"=>"Error", "4"=>"Held for Review");
    }

    public function register() {
        
    }

    public function setMerchantAuthentication(){
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($this->APILoginId);
        $merchantAuthentication->setTransactionKey($this->APIKey); 
        
        return $merchantAuthentication;
    }
    
    public function setCreditCard($cardDetails){  
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardDetails["card-number"]);
        $creditCard->setExpirationDate($cardDetails["year-month"]);
        $creditCard->setCardCode($cardDetails["card-cvc"]);

        $paymentType = new AnetAPI\PaymentType();
        $paymentType->setCreditCard($creditCard);
        
        return $paymentType;
    }
    
    public function setTransactionRequestType($paymentType, $amount, $TrackingNumber, $from_firstname, $from_lastName, $from_email, $from_phone_number, $from_address, $from_city, $from_state, $from_postcode, $from_country){   
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($TrackingNumber);
        $order->setDescription("Shipping Payment for Recycled Bidi Sticks");

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId($TrackingNumber);
        $customerData->setEmail($from_email);

        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($from_firstname);
        $customerAddress->setLastName($from_lastName);
        $customerAddress->setCompany("");
        $customerAddress->setPhoneNumber($from_phone_number);
        $customerAddress->setAddress($from_address);
        $customerAddress->setCity($from_city);
        $customerAddress->setState($from_state);
        $customerAddress->setZip($from_postcode);
        $customerAddress->setCountry($from_country);

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setPayment($paymentType);
        $transactionRequestType->setCustomer($customerData);        
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setOrder($order);

        return $transactionRequestType;
    }

    public function chargeCreditCard($cardDetails, $amount, $TrackingNumber, $from_firstname, $from_lastName, $from_email, $from_phone_number, $from_address, $from_city, $from_state, $from_postcode, $from_country){
        $paymentType = $this->setCreditCard($cardDetails);
        $transactionRequestType = $this->setTransactionRequestType($paymentType, $amount, $TrackingNumber, $from_firstname, $from_lastName, $from_email, $from_phone_number, $from_address, $from_city, $from_state, $from_postcode, $from_country);       

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setRefId($this->refId);
        $request->setTransactionRequest($transactionRequestType);    

        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        return $response;
    }
    
}