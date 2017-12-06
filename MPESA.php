;<?php
/**
* @package ModoPesa MPESA - PHP
* @subpackage Main MPESA Class
* @version 0.17.12
* @author Mauko Maunde < hi@mauko.co.ke >
* @see https://developer.safaricom.co.ke/docs
**/

namespace Safaricom;

class MPESA
{
    // Business Name as registered with Safaricom MPESA
    private $business;

    // Six-digit MPESA business Till or Paybill number
    private $shortcode;

    // Daraja app key
    private $consumer_key;

    // Daraja appsecret
    private $consumer_secret;

    // MPESA web portal username
    private $username;

    // MPESA web portal password
    private $password;

    // The path to MPESA Public Key (the cert.cr file )
    private $publicKey;

    // The path that stores information of time out transactions.it should be properly validated to make sure that it contains the port, URI and domain name or publicly available IP.
    private $timeout_url;

    // The path that receives results from M-Pesa it should be properly validated to make sure that it contains the port, URI and domain name or publicly available IP.
    private $result_url;

    // The path that receives results from M-Pesa it should be properly validated to make sure that it contains the port, URI and domain name or publicly available IP.
    private $confirmation_url;

    //The path that receives results from M-Pesa it should be properly validated to make sure that it contains the port, URI and domain name or publicly available IP.
    private $validation_url;

    // Whether this is a production environment or not
    protected $live;

    /**
     * Constructor method for our MPESA class.
     * @param boolean $live Whether this is a production environment or not
     * @param string $public_key The path to MPESA Public Key (the cert.cr file ) 
     */
    public function __construct( $live = true, $public_key = "cert.cr" )
    {
        $this -> business = MPESA_NAME;
        $this -> shortcode = MPESA_SHORTCODE;
        $this -> type = MPESA_ID_TYPE;
        $this -> key = MPESA_KEY;
        $this -> secret = MPESA_SECRET;
        $this -> username = MPESA_USERNAME;
        $this -> password = MPESA_PASSWORD;
        $this -> publicKey = $public_key;
        $this -> timeout_url = MPESA_TIMEOUT_URL;
        $this -> result_url = MPESA_RESULT_URL;
        $this -> confirmation_url = MPESA_CONFIRMATION_URL;
        $this -> validation_url = MPESA_VALIDATION_URL;

        $this -> live = $live;
    }

    /**
     * Use this code to authenticate your app and get an OAuth access token. 
     * An access token expires in 3600 seconds (1 hour)
     */
    private function auth()
    {
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url );
        $credentials = base64_encode( $this -> key.':'.$this -> secret );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Content-Type:application/json', 'Authorization: Basic '.$credentials ) );
        curl_setopt( $curl, CURLOPT_HEADER, true );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );

        $curl_response = curl_exec( $curl );

        return json_decode( $curl_response );
    }

    /**
     * Generate security credentials by encrypting the base64 encoded initiator password with M-Pesa’s public key, a X509 certificate.
     * @return mixed-string The security credentials
     */
    private function securityCredential()
    {
        openssl_public_encrypt( $this -> pass, $encrypted, $this -> publicKey, OPENSSL_PKCS1_PADDING );

        return base64_encode( $encrypted );
    }

    /**
     * This is used to generate tokens for the live environment
     * @return mixed
     */
    public function liveToken()
    {
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials));
        curl_setopt( $curl, CURLOPT_HEADER, false );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);

        $curl_response = curl_exec( $curl );

        return json_decode( $curl_response)->access_token;
    }

    /**
     * use this function to generate a sandbox token
     * @return mixed
     */
    public static function sandBoxToken()
    {
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
        curl_setopt( $curl, CURLOPT_HEADER, false );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);

        $curl_response = curl_exec( $curl );

        return json_decode( $curl_response) -> access_token;
    }

    /**
     *  Whenever M-Pesa receives a transaction on the shortcode, M-Pesa triggers a validation request against the validation URL and the 3rd party system responds to M-Pesa with a validation response (either a success or an error code).
     **/
    public function register()
    {
        $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token)); 

        $curl_post_data = array(
          //Fill in the request parameters with valid values
          'ShortCode' => $this -> shortcode,
          'ResponseType' => 'Completed',
          'ConfirmationURL' => $this -> confirmation_url,
          'ValidationURL' => $this -> validation_url
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);
        print_r($curl_response);

        return json_encode( $curl_response );
    }

    /**
     * REVERSAL API 
     * Use this function to initiate a reversal request
     * @param $CommandID - Takes only 'TransactionReversal' Command id
     * @param $this -> username - The name of Initiator to initiating  the request
     * @param $TransactionID - Organization Receiving the funds
     * @param $Amount - Amount
     * @param $ReceiverParty - Organization /MSISDN sending the transaction
     * @param $RecieverIdentifierType - Type of organization receiving the transaction
     * @param $Remarks - Comments that are sent along with the transaction.
     * @param $Occasion -   Optional Parameter
     * @return mixed-string
     */
    public function reverse( $TransactionID, $Amount, $ReceiverParty, $RecieverIdentifierType, $Remarks, $Occasion )
    {
        if( $this -> live ){
            $url = 'https://api.safaricom.co.ke/mpesa/reversal/v1/request';
            $token = $this -> liveToken();
        } else {
            $url = 'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request';
            $token = $this -> sandBoxToken();
        }

        $curl = curl_init();

        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));

        $curl_post_data = array(
            'CommandID' => 'TransactionReversal',
            'Initiator' => $this -> business,
            'SecurityCredential' => $this -> securityCredential(),
            'TransactionID' => $TransactionID,
            'Amount' => $Amount,
            'ReceiverParty' => $ReceiverParty,
            'RecieverIdentifierType' => $RecieverIdentifierType,
            'ResultURL' => $this -> result_url,
            'QueueTimeOutURL' => $this -> timeout_url,
            'Remarks' => $Remarks,
            'Occasion' => $Occasion
        );

        $data_string = json_encode( $curl_post_data );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        $curl_response = curl_exec( $curl );
        return json_decode( $curl_response);
    }

    /**
     * B2C API
     * @param $CommandID - Unique command for each transaction SalaryPayment | BusinessPayment | PromotionPayment
     * @param $Amount - The amount being transacted
     * @param $PartyB - Phone number receiving the transaction
     * @param $Remarks - Comments that are sent along with the transaction.
     * @param $Occasion -   Optional
     * @return string
     */
    public function b2c( $CommandID, $Amount, $PartyB, $Remarks = "", $Occasion = "" )
    {
        if( $this -> live ){
            $url = 'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
            $token = $this -> liveToken();
        } else {
            $url = 'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
            $token = $this -> sandBoxToken();
        }

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token)); 

        $curl_post_data = array(
            'InitiatorName' => $this -> username,
            'SecurityCredential' => $this -> securityCredential(),
            'CommandID' => $CommandID ,
            'Amount' => $Amount,
            'PartyA' => $this -> shortcode ,
            'PartyB' => $PartyB,
            'Remarks' => $Remarks,
            'QueueTimeOutURL' => $this -> timeout_url,
            'ResultURL' => $this -> result_url,
            'Occasion' => $Occasion
        );

        $data_string = json_encode( $curl_post_data );

        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );

        $curl_response = curl_exec( $curl );
        print_r( $curl_response);

        return json_decode( $curl_response );
    }

    /**
     * B2B API
     * @param $Amount - Amount
     * @param $PartyB - Organization’s short code receiving the funds being transacted.
     * @param $Remarks - Comments that are sent along with the transaction.
     * @param $AccountReference - Account Reference mandatory for “BusinessPaybill” CommandID.
     * @param $commandID - Unique command for each transaction - BusinessPayBill | MerchantToMerchantTransfer | MerchantTransferFromMerchantToWorking |  MerchantServicesMMFAccountTransfer |  AgencyFloatAdvance
     * @param $RecieverIdentifierType - Type of organization receiving the funds being transacted - MSISDN | Till Number | Shortcode
     * @return mixed-string
     */
    public function b2b( $Amount, $PartyB, $Remarks, $AccountReference, $commandID, $RecieverIdentifierType )
    {
        if( $this -> live ){
            $url = 'https://api.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';
            $token = $this -> liveToken();
        } else {
            $url = 'https://sandbox.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';
            $token = $this -> sandBoxToken();
        }
        
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));
        $curl_post_data = array(
            'Initiator' => $this -> username,
            'SecurityCredential' => $this -> securityCredential(),
            'CommandID' => $commandID,
            'SenderIdentifierType' => $this -> type,
            'RecieverIdentifierType' => $RecieverIdentifierType,
            'Amount' => $Amount,
            'PartyA' => $this -> shortcode,
            'PartyB' => $PartyB,
            'AccountReference' => $AccountReference,
            'Remarks' => $Remarks,
            'QueueTimeOutURL' => $this -> timeout_url,
            'ResultURL' => $this -> result_url
        );
        $data_string = json_encode( $curl_post_data );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
        $curl_response = curl_exec( $curl );

        return json_decode( $curl_response );
    }

    /**
     * C2B API
     * This API enables Paybill and Buy Goods merchants to integrate to M-Pesa and receive real time payments notifications.
     * @param $CommandID - Unique command for each transaction type.
     * @param $Amount - The amount being transacted.
     * @param $Msisdn - MSISDN (phone number) sending the transaction, start with country code without the plus(+) sign.
     * @param $BillRefNumber -  Bill Reference Number (Optional).
     * @return mixed-string
     */
    public function  c2b( $Amount, $Msisdn, $BillRefNumber = "", $CommandID = "CustomerPayBillOnline" )
    {
        if( $this -> live ) {
            $url = 'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate';
            $token = $this -> liveToken();
        } else {
            $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
            $token = $this -> sandBoxToken();
        }

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt( 
          $curl, 
          CURLOPT_HTTPHEADER, ['Content-Type:application/json','Authorization:Bearer '.$token] );

        $curl_post_data = array(
            'ShortCode' => $this -> shortcode,
            'CommandID' => $CommandID,
            'Amount' => $Amount,
            'Msisdn' => $Msisdn,
            'BillRefNumber' => $BillRefNumber
        );

        $data_string = json_encode( $curl_post_data );

        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        $curl_response = curl_exec( $curl );

        return json_decode( $curl_response );
    }

    /**
     * Use this to initiate a balance inquiry request
     * @param $CommandID - A unique command passed to the M-Pesa system.
     * @param $IdentifierType -Type of organization receiving the transaction
     * @param $Remarks - Comments that are sent along with the transaction.
     * @return mixed-string
     */
    public function balance( $CommandID, $IdentifierType, $Remarks = "" )
    {
        if( $this -> live ){
            $url = 'https://api.safaricom.co.ke/mpesa/accountbalance/v1/query';
            $token = $this -> liveToken();
        } else {
            $url = 'https://sandbox.safaricom.co.ke/mpesa/accountbalance/v1/query';
            $token = $this -> sandBoxToken();
        }
        

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json','Authorization:Bearer '.$token] ); 

        $curl_post_data = array(
            'CommandID' => $CommandID,
            'Initiator' => $this -> username,
            'SecurityCredential' => $this -> securityCredential(),
            'PartyA' => $this -> shortcode,
            'IdentifierType' => $IdentifierType,
            'Remarks' => $Remarks,
            'QueueTimeOutURL' => $this -> timeout_url,
            'ResultURL' => $this -> result_url
        );

        $data_string = json_encode( $curl_post_data );

        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        $curl_response = curl_exec( $curl );

        echo( $curl_response );
    }

    /**
     * Use this function to make a transaction status request
     * @param $this -> username - The name of Initiator to initiating the request.
     * @param $CommandID - Unique command for each transaction type, possible values are: TransactionStatusQuery.
     * @param $TransactionID - Organization Receiving the funds.
     * @param $IdentifierType - Type of organization receiving the transaction
     * @param $Remarks -    Comments that are sent along with the transaction
     * @param $Occasion -   Optional Parameter
     * @return mixed-string
     */
    public function status( $TransactionID, $Initiator = null, $IdentifierType = null, $CommandID = "TransactionStatusQuery", $Remarks = "", $Occasion = "" )
    {
        if( $this -> live ){
            $url = 'https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query';
            $token = $this -> liveToken();
        } else {
            $url = 'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query';
            $token = $this -> sandBoxToken();
        }

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));

        $Initiator = is_null( $Initiator ) ? $this -> username : $Initiator;
        $IdentifierType = is_null( $IdentifierType ) ? $this -> type : $IdentifierType
        $curl_post_data = array(
            'Initiator' => $Initiator,
            'SecurityCredential' => $this -> securityCredential(),
            'CommandID' => $CommandID,
            'TransactionID' => $TransactionID,
            'PartyA' => $this -> shortcode,
            'IdentifierType' => $IdentifierType,
            'ResultURL' => $this -> result_url,
            'QueueTimeOutURL' => $this -> timeout_url,
            'Remarks' => $Remarks,
            'Occasion' => $Occasion
        );

        $data_string = json_encode( $curl_post_data );

        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        $curl_response = curl_exec( $curl );

        echo ( $curl_response );
    }

    /**
     * Use this function to initiate an STKPush Simulation
     * @param $Amount - The amount to be transacted.
     * @param $PartyB - The organization shortcode receiving the funds
     * @param $PhoneNumber - The MSISDN sending the funds.
     * @param $AccountReference - Used with M-Pesa PayBills.
     * @param $TransactionDesc - A description of the transaction.
     * @param $Remark - Remarks
     * @return mixed-string
     */
    public function pay( $Amount, $PhoneNumber, $AccountReference = "", $TransactionDesc = "", $Remarks = "" )
    {
        if( $this -> live ){
            $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            $token = $this -> liveToken();
        } else {
            $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            $token = $this -> sandBoxToken();
        }

        $timestamp = date("yyyymmddhhiiss");
        $password = base64_encode( $this -> shortcode.$this -> password.$timestamp );

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json','Authorization:Bearer '.$token] );


        $curl_post_data = array(
            'BusinessShortCode' => $this -> shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $Amount,
            'PartyA' => $PhoneNumber,
            'PartyB' => $this -> shortcode,
            'PhoneNumber' => $PhoneNumber,
            'CallBackURL' => $this -> callback_url,
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionType,
            'Remark'=> $Remarks
        );

        $data_string = json_encode( $curl_post_data );

        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        $curl_response=curl_exec( $curl );

        return json_decode( $curl_response );
    }

    /**
     * Use this function to initiate an STKPush Status Query request.
     * @param $checkoutRequestID - Checkout RequestID
     * @return mixed-string
     */
    public function stkpush( $checkoutRequestID )
    {
        if( $this -> live ){
            $url = 'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query';
            $token = $this -> liveToken();
        } else {
            $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
            $token = $this -> sandBoxToken();
        }

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));
        $timestamp = date("yyyymmddhhiiss");
        $password = base64_encode( $this -> shortcode.$this -> password.$timestamp );

        $curl_post_data = array(
            'BusinessShortCode' => $this -> shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestID
        );

        $data_string = json_encode( $curl_post_data );

        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
        curl_setopt( $curl, CURLOPT_HEADER, false );

        $curl_response = curl_exec( $curl );

        return json_decode( $curl_response );
    }

    public function simulate( $Amount, $Msisdn )
    {
        $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer ACCESS_TOKEN')); //setting custom header

        $curl_post_data = array(
             'ShortCode' => $this -> shortcode,
             'CommandID' => 'CustomerPayBillOnline',
             'Amount' => $Amount,
             'Msisdn' => $Msisdn,
             'BillRefNumber' => '00000'
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);
        print_r($curl_response);

        return json_decode( $curl_response );
    }

    public function finish($ok = true )
    {
        if ( $ok ) {
            $response = array( 
                'ResponseCode' => 0,
                'ResponseDesc' => 'Transaction Accepted Successfully'
            );
        } else{
            $response = array( 
                'ResponseCode' => 0,
                'ResponseDesc' => 'Transaction Rejected'
            );
        }

        header( "Content-Type: application/json" );
        echo( json_encode( $response ) );
    }
}