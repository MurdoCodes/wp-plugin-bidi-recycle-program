<?php
/**
* @package Bidi Recycle Program
*/
namespace Includes\StampsAPI;

class Credentials {
    public $IntegrationID;
    public $Username;
    public $Password;

    public function __construct() {      
        
        /**
        * DEVELOPMENT CREDENTIALS        
        **/
            $this->IntegrationID = "a560af9f-8c5c-4546-969c-6f6d0111401a";
            $this->Username = "Kaival-001";
            $this->Password = "May2020!";
		

        /**
        * PRODUCTION CREDENTIALS
        **/  
            // $this->IntegrationID = "a560af9f-8c5c-4546-969c-6f6d0111401a";
            // $this->Username = "emosser-4460ol";
            // $this->Password = "#Melbourne121";
            
    }
}