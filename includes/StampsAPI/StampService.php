<?php
/**
* @package Bidi Recycle Program
*/
namespace Includes\StampsAPI;
define('ADDRESS_UNSHIPPABLE', -1);
define('ADDRESS_MATCH', 1);
define('ADDRESS_CITYSTATEZIPOK', 2);

class StampService {
    /** 
    * Development URL
    **/  
    private const URL = "https://swsim.testing.stamps.com/swsim/swsimv90.asmx?wsdl";

    /**
    * Production URL
    **/
    // private const URL = "https://swsim.stamps.com/swsim/swsimv90.asmx?wsdl";
    
    private $soapClient;
    private $authenticator;
    private $toAddress;
    private $integratorTxID;

    public function __construct($toAddress = NULL) {
        $this->soapClient = new \SoapClient(StampService::URL);
        $this->authenticateUser();
        if(isset($toAddress)) {
            $res = $this->cleanseAddress($toAddress);
            if($res["status"] == ADDRESS_MATCH || $res["status"] == ADDRESS_CITYSTATEZIPOK) {
                $this->toAddress = $res["address"];
            } else {
                throw new InvalidArgumentException('Address passed is invalid');
            }
        } else {
            $res = $this->cleanseAddress(new Address(
                'BIDICARES',
                'RETURNS',
                '8630 SW Scholls Ferry Rd Suite 3333',
                'Beaverton',
                'Oregon',
                '97008',
                '8333672434',
                'support@bidivapor.com'
            ));
            $this->toAddress = $res["address"];
        }
        $this->integratorTxID = md5(rand());
    }

    
    public function register() {
        
    }

    private function authenticateUser(){
        if(isset($this->soapClient)) {
            try {
                $credentials = new Credentials();                
                $response = $this->soapClient->AuthenticateUser(array("Credentials"=>(array) $credentials));
                if (isset($response->Authenticator)) {
                    $this->authenticator = $response->Authenticator;
                    $this->_log(LOG_INFO, "Authentication Successful");
                    $this->_log(LOG_INFO, $this->authenticator);
                } else {
                    $this->_log(LOG_ERR, "Authentication Error. Authenticator not set.");
                }
                
            } catch (Exception $e) {
                $this->_log(LOG_ERR, $e->getMessage());
                throw $e;
            }
        } else {
            throw new Exception("SOAP CLIENT NOT INITIALIZED!");
        }
    }

    private function getAccountInfo() {
        if(isset($this->soapClient)) {
            try {
                if (!isset($this->authenticator)) {
                    throw new Exception("Authenticator not set!");
                }                
                $response = $this->soapClient->GetAccountInfo(array('Authenticator'=>$this->authenticator));
                if (isset($response->AccountInfo)) {
                    $this->_log(LOG_INFO, "GetAccountInfo Successful");
                    $this->authenticator = $response->Authenticator;
                    return $response;
                } else {
                    $this->_log(LOG_ERR, "Get Account Info Error. AccountInfo not set.");
                    return NULL;
                }
            } catch (Exception $e) {
                $this->_log(LOG_ERR, $e->getMessage());
                throw $e;
            }
        } else {
            throw new Exception("SOAP CLIENT NOT INITIALIZED!");
        }
    }
    public function purchasePostage(float $amount){
        if(isset($this->soapClient)) {
            try {
                if(!isset($amount)) {
                    throw new InvalidArgumentException('Amount is required');
                }
                $currentAccountInfo = $this->getAccountInfo();

                if(isset($currentAccountInfo)) {
                    $control_total = $currentAccountInfo->AccountInfo->PostageBalance->ControlTotal;

                    $this->_log(LOG_INFO, "Purchase Postage: Current control total = " . $control_total);

                    $response = $this->soapClient->PurchasePostage(array("Authenticator"=>$this->authenticator,
                                                                        "PurchaseAmount"=>$amount,
                                                                        "ControlTotal"=> $control_total,
                                                                        "IntegratorTxID"=>$this->integratorTxID));
                    if (isset($response->PurchaseStatus) && $response->PurchaseStatus == "Success") {
                        $this->_log(LOG_INFO, "Purchase Postage: Success");
                        $this->_log(LOG_INFO, "Purchase Postage: Transaction ID #" . $response->TransactionID);
                        $this->_log(LOG_INFO, "Purchase Postage: New control total = " . $response->PostageBalance->ControlTotal);

                        $this->authenticator = $response->Authenticator;

                        return TRUE;
                    } else {
                        $this->_log(LOG_ERR,'Purchase Postage Failed!');
                        return FALSE;
                    }
                }
            } catch (Exception $e) {
                $this->_log(LOG_ERR, $e->getMessage());
                throw $e;
            }
        } else {
            throw new Exception("SOAP CLIENT NOT INITIALIZED!");
        } 
    }
    public function cleanseAddress(Address $address) {
        if(isset($this->soapClient)) {
            try {
                if(!isset($address)) {
                    throw new InvalidArgumentException('Address object is required');
                }
                
                $this->_log(LOG_INFO, 'Cleansing address...');
    
                $params = array(
                    "Authenticator" => $this->authenticator,
                    "Address" => (array) $address
                );
                
                $response = $this->soapClient->CleanseAddress($params);
                // save new authenticator
                $this->authenticator = $response->Authenticator;

                if (!$response->CityStateZipOK) {
                    // Cant proceed. Unshippable address.
                    $this->_log(LOG_ERR, 'Unshippable address');
                    return array("status" => ADDRESS_UNSHIPPABLE);
                }
                
                if ($response->AddressMatch) {
                    // Address is good
                    return array("status" => ADDRESS_MATCH,
                                "address" => $response->Address);
                } else if ($response->CityStateZipOK) {
                    // Address Issue
                    // City state zip are good
                    $address->OverrideHash = $response->Address->OverrideHash;
                    return array("status" => ADDRESS_CITYSTATEZIPOK,
                                "address" => $response->Address);
                }
            } catch (Exception $e) {
                $this->_log(LOG_ERR, $e->getMessage());
                throw $e;
            }
            
        } else {
            throw new Exception("SOAP CLIENT NOT INITIALIZED!");
        } 
    }
    public function getRates(string $fromZIPCode, float $weightLb, float $weightOz, string $serviceType = NULL, string $packageType = NULL,
                             float $length = NULL, float $width = NULL, float $height = NULL,  string $shipDate = NULL) {
        if(isset($this->soapClient)) {
            try {
                if(!isset($fromZIPCode) && !isset($weightLb) && !isset($weightOz)){
                    throw new InvalidArgumentException('From ZIP code and Weights are required!');
                }

                $params = array("Authenticator" => $this->authenticator,
                                "Rate" => array(
                                    "FromZipCode" => $fromZIPCode,
                                    "ToZIPCode" => $this->toAddress->ZIPCode,
                                    "WeightLb" => $weightLb,
                                    "WeightOz" => $weightOz,
                                    "ServiceType" => isset($serviceType) ? $serviceType : '',
                                    "PackageType" => isset($packageType) ? $packageType : '',
                                    "Length" => isset($length) ? $length : '',
                                    "Width" => isset($width) ? $width : '',
                                    "Height" => isset($height) ? $height : '',
                                    "ShipDate" => isset($shipDate) ? $shipDate : date('yy-m-d', strtotime('+ 3 days')),
                                )
                );

                $response = $this->soapClient->GetRates($params);
                $this->authenticator = $response->Authenticator;
                // Remove AddOns for now
                if(is_array($response->Rates->Rate)){
                    foreach ($response->Rates->Rate as $key => $value) {
                        unset($response->Rates->Rate[$key]->AddOns);
                    }
                } else {
                    unset($response->Rates->Rate->AddOns);
                }
                
                return $response->Rates->Rate;
            } catch (Exception $e) {
                _log(LOG_ERR, $e->getMessage());
                throw $e;
            }
        } else {
            throw new Exception("SOAP CLIENT NOT INITIALIZED!");
        } 
    }
    public function generateShippingLabel($fromAddress, $rate) {
        if(isset($this->soapClient)) {
            try {
                if(!isset($fromAddress) && !isset($rate)) {
                    throw new InvalidArgumentException('From Address and Rate are required!');
                }

                if(!isset($fromAddress->CleanseHash) && !isset($fromAddress->OverrideHash)) {
                    throw new InvalidArgumentException('Address must be cleansed first. Call cleanseAddress() first.');
                }
                
                $rate = (array) $rate;
                $rate["FromZIPCode"] = $fromAddress->ZIPCode;

                // $res = $this->cleanseAddress(new KaivalAddress());
                // $this->toAddress = $res["address"];


                $params = array(
                    "Authenticator" => $this->authenticator,
                    "IntegratorTxID" => $this->integratorTxID,
                    "Rate" => $rate,
                    "From" => (array) $fromAddress,
                    "To" => (array) $this->toAddress,
                    //"SampleOnly" => true
                );

                $response = $this->soapClient->CreateIndicium($params);
                $this->authenticator = $response->Authenticator;

                $ret = array(
                    "TrackingNumber" => $response->TrackingNumber,
                    "StampsTxID" => $response->StampsTxID,
                    "URL" => $response->URL,
                    "Rate" => (array) $response->Rate
                );
                return $ret;
                
            } catch (Exception $e) {
                $this->_log(LOG_ERR, $e->getMessage());
                throw $e;
            }
        } else {
            throw new Exception("SOAP CLIENT NOT INITIALIZED!");
        }
    }
    private function _log($level, $message) {
        openlog("StampsAPILog", LOG_CONS | LOG_NDELAY | LOG_PID, LOG_USER | LOG_PERROR);
        syslog($level, $message);
        closelog();
    }

}