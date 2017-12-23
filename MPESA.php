<?php
/**
* @package ModoPesa MPESA - PHP
* @subpackage Main MPESA Class
* @version 0.17.12
* @author Mauko Maunde < hi@mauko.co.ke >
* @link https://developer.safaricom.co.ke/docs
**/

namespace Safaricom;

/**
* @class MPESA - Core Safaricom MPESA wrapper class for all APIs
* @see https://developer.safaricom.co.ke/docs
* Endpoints should be properly validated to make sure that they contain the port, URI and domain name or publicly available IP.
**/
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

    // The MPESA Public Key
    private $publicKey = '-----BEGIN CERTIFICATE-----
MIIGkzCCBXugAwIBAgIKXfBp5gAAAD+hNjANBgkqhkiG9w0BAQsFADBbMRMwEQYK
CZImiZPyLGQBGRYDbmV0MRkwFwYKCZImiZPyLGQBGRYJc2FmYXJpY29tMSkwJwYD
VQQDEyBTYWZhcmljb20gSW50ZXJuYWwgSXNzdWluZyBDQSAwMjAeFw0xNzA0MjUx
NjA3MjRaFw0xODAzMjExMzIwMTNaMIGNMQswCQYDVQQGEwJLRTEQMA4GA1UECBMH
TmFpcm9iaTEQMA4GA1UEBxMHTmFpcm9iaTEaMBgGA1UEChMRU2FmYXJpY29tIExp
bWl0ZWQxEzARBgNVBAsTClRlY2hub2xvZ3kxKTAnBgNVBAMTIGFwaWdlZS5hcGlj
YWxsZXIuc2FmYXJpY29tLmNvLmtlMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIB
CgKCAQEAoknIb5Tm1hxOVdFsOejAs6veAai32Zv442BLuOGkFKUeCUM2s0K8XEsU
t6BP25rQGNlTCTEqfdtRrym6bt5k0fTDscf0yMCoYzaxTh1mejg8rPO6bD8MJB0c
FWRUeLEyWjMeEPsYVSJFv7T58IdAn7/RhkrpBl1dT7SmIZfNVkIlD35+Cxgab+u7
+c7dHh6mWguEEoE3NbV7Xjl60zbD/Buvmu6i9EYz+27jNVPI6pRXHvp+ajIzTSsi
eD8Ztz1eoC9mphErasAGpMbR1sba9bM6hjw4tyTWnJDz7RdQQmnsW1NfFdYdK0qD
RKUX7SG6rQkBqVhndFve4SDFRq6wvQIDAQABo4IDJDCCAyAwHQYDVR0OBBYEFG2w
ycrgEBPFzPUZVjh8KoJ3EpuyMB8GA1UdIwQYMBaAFOsy1E9+YJo6mCBjug1evuh5
TtUkMIIBOwYDVR0fBIIBMjCCAS4wggEqoIIBJqCCASKGgdZsZGFwOi8vL0NOPVNh
ZmFyaWNvbSUyMEludGVybmFsJTIwSXNzdWluZyUyMENBJTIwMDIsQ049U1ZEVDNJ
U1NDQTAxLENOPUNEUCxDTj1QdWJsaWMlMjBLZXklMjBTZXJ2aWNlcyxDTj1TZXJ2
aWNlcyxDTj1Db25maWd1cmF0aW9uLERDPXNhZmFyaWNvbSxEQz1uZXQ/Y2VydGlm
aWNhdGVSZXZvY2F0aW9uTGlzdD9iYXNlP29iamVjdENsYXNzPWNSTERpc3RyaWJ1
dGlvblBvaW50hkdodHRwOi8vY3JsLnNhZmFyaWNvbS5jby5rZS9TYWZhcmljb20l
MjBJbnRlcm5hbCUyMElzc3VpbmclMjBDQSUyMDAyLmNybDCCAQkGCCsGAQUFBwEB
BIH8MIH5MIHJBggrBgEFBQcwAoaBvGxkYXA6Ly8vQ049U2FmYXJpY29tJTIwSW50
ZXJuYWwlMjBJc3N1aW5nJTIwQ0ElMjAwMixDTj1BSUEsQ049UHVibGljJTIwS2V5
JTIwU2VydmljZXMsQ049U2VydmljZXMsQ049Q29uZmlndXJhdGlvbixEQz1zYWZh
cmljb20sREM9bmV0P2NBQ2VydGlmaWNhdGU/YmFzZT9vYmplY3RDbGFzcz1jZXJ0
aWZpY2F0aW9uQXV0aG9yaXR5MCsGCCsGAQUFBzABhh9odHRwOi8vY3JsLnNhZmFy
aWNvbS5jby5rZS9vY3NwMAsGA1UdDwQEAwIFoDA9BgkrBgEEAYI3FQcEMDAuBiYr
BgEEAYI3FQiHz4xWhMLEA4XphTaE3tENhqCICGeGwcdsg7m5awIBZAIBDDAdBgNV
HSUEFjAUBggrBgEFBQcDAgYIKwYBBQUHAwEwJwYJKwYBBAGCNxUKBBowGDAKBggr
BgEFBQcDAjAKBggrBgEFBQcDATANBgkqhkiG9w0BAQsFAAOCAQEAC/hWx7KTwSYr
x2SOyyHNLTRmCnCJmqxA/Q+IzpW1mGtw4Sb/8jdsoWrDiYLxoKGkgkvmQmB2J3zU
ngzJIM2EeU921vbjLqX9sLWStZbNC2Udk5HEecdpe1AN/ltIoE09ntglUNINyCmf
zChs2maF0Rd/y5hGnMM9bX9ub0sqrkzL3ihfmv4vkXNxYR8k246ZZ8tjQEVsKehE
dqAmj8WYkYdWIHQlkKFP9ba0RJv7aBKb8/KP+qZ5hJip0I5Ey6JJ3wlEWRWUYUKh
gYoPHrJ92ToadnFCCpOlLKWc0xVxANofy6fqreOVboPO0qTAYpoXakmgeRNLUiar
0ah6M/q/KA==
-----END CERTIFICATE-----';

    private $http_status_code = [
        200 => 'Success',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable – You requested a format that isn’t json',
        429 => 'Too Many Requests – You’re requesting too many kisses! Slow down!',
        500 => 'Internal Server Error – We had a problem with our server. Try again later.',
        503 => 'Service Unavailable – We’re temporarily offline for maintenance. Please try again later.'
    );

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
     * Constructor method for our MPESA Core class.
     * @param boolean $live Whether this is a production environment or not
     * @param string $public_key The path to MPESA Public Key (the cert.cr file ) 
     */
    public function __construct( $live = true, $public_key = null )
    {
        if( defined( 'MPESA_CONFIG' ) && is_[ MPESA_CONFIG ) ){
          $this -> business = MPESA_CONFIG['NAME'];
          $this -> shortcode = MPESA_CONFIG['SHORTCODE'];
          $this -> type = MPESA_CONFIG['ID_TYPE'];
          $this -> key = MPESA_CONFIG['KEY'];
          $this -> secret = MPESA_CONFIG['SECRET'];
          $this -> username = MPESA_CONFIG['USERNAME'];
          $this -> password = MPESA_CONFIG['PASSWORD'];
          $this -> callback_url = MPESA_CONFIG['CALLBACK_URL'];
          $this -> timeout_url = MPESA_CONFIG['TIMEOUT_URL'];
          $this -> result_url = MPESA_CONFIG['RESULT_URL'];
          $this -> confirmation_url = MPESA_CONFIG['CONFIRMATION_URL'];
          $this -> validation_url = MPESA_CONFIG['VALIDATION_URL'];
        } else {
          $this -> business = MPESA_NAME;
          $this -> shortcode = MPESA_SHORTCODE;
          $this -> type = MPESA_ID_TYPE;
          $this -> key = MPESA_KEY;
          $this -> secret = MPESA_SECRET;
          $this -> username = MPESA_USERNAME;
          $this -> password = MPESA_PASSWORD;
          $this -> callback_url = MPESA_CALLBACK_URL;
          $this -> timeout_url = MPESA_TIMEOUT_URL;
          $this -> result_url = MPESA_RESULT_URL;
          $this -> confirmation_url = MPESA_CONFIRMATION_URL;
          $this -> validation_url = MPESA_VALIDATION_URL;
        }

        $this -> live = $live;

        if !is_null( $public_key ) { $this -> publicKey = $public_key; }
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
        curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Content-Type:application/json', 'Authorization: Basic '.$credentials ] );
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
        $credentials = base64_encode($this -> key.':'.$this -> secret);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Authorization: Basic '.$credentials ] );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);

        $curl_response = curl_exec( $curl );

        return json_decode( $curl_response ) -> access_token;
    }

    /**
     * use this function to generate a sandbox token
     * @return mixed
     */
    public function sandBoxToken()
    {
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url);
        $credentials = base64_encode($this -> key.':'.$this -> secret);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Authorization: Basic '.$credentials ] );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);

        $curl_response = curl_exec( $curl );

        return json_decode( $curl_response ) -> access_token;
    }

    /**
     *  Whenever M-Pesa receives a transaction on the shortcode, M-Pesa triggers a validation request against the validation URL 
     * and the 3rd party system responds to M-Pesa with a validation response (either a success or an error code).
     */
    public function register()
    {
        $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [ 'Content-Type:application/json','Authorization:Bearer '.$token ] ); 

        $curl_post_data = [
          'ShortCode' => $this -> shortcode,
          'ResponseType' => 'Completed',
          'ConfirmationURL' => $this -> confirmation_url,
        ];

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);

        return json_decode( $curl_response );
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
      $url = ( $this -> live === true ) ? 'https://api.safaricom.co.ke/mpesa/reversal/v1/request' : 'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request';
      $token = ( $this -> live === true ) ? $this -> liveToken() : $this -> sandBoxToken();

      $curl = curl_init();

      curl_setopt( $curl, CURLOPT_URL, $url);
      curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Content-Type:application/json','Authorization:Bearer '.$token ] );

      $curl_post_data = [
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
      ];

      $data_string = json_encode( $curl_post_data );

      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $curl, CURLOPT_POST, true );
      curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
      curl_setopt( $curl, CURLOPT_HEADER, false );

      $curl_response = curl_exec( $curl );

      return json_decode( $curl_response );
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
      $url = ( $this -> live === true ) ? 'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest' : 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
      $token = ( $this -> live === true ) ? $this -> liveToken() : $this -> sandBoxToken();

      $curl = curl_init();

      curl_setopt( $curl, CURLOPT_URL, $url);
      curl_setopt( $curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json','Authorization:Bearer '.$token ] ); 

      $curl_post_data = [
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
      ];

      $data_string = json_encode( $curl_post_data );

      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $curl, CURLOPT_POST, true );
      curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );

      $curl_response = curl_exec( $curl );

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
      $url = ( $this -> live === true ) ? 'https://api.safaricom.co.ke/mpesa/b2b/v1/paymentrequest' : 'https://sandbox.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';
      $token = ( $this -> live === true ) ? $this -> liveToken() : $this -> sandBoxToken();

      $curl = curl_init();

      curl_setopt( $curl, CURLOPT_URL, $url);
      curl_setopt( $curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json','Authorization:Bearer '.$token ] );

      $curl_post_data = [
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
      ];

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
    public function  c2b( $Amount, $Msisdn, $BillRefNumber = null, $CommandID = "CustomerPayBillOnline" )
    {
      $url = ( $this -> live === true ) ? 'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate' : 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
      $token = ( $this -> live === true ) ? $this -> liveToken() : $this -> sandBoxToken();

      $curl = curl_init();

      curl_setopt( $curl, CURLOPT_URL, $url);
      curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Content-Type:application/json', 'Authorization:Bearer '.$token ] );

      $BillRefNumber = is_null( $BillRefNumber ) ? rand( 0, 1000000 ) : $BillRefNumber;

      $curl_post_data = [
          'ShortCode' => $this -> shortcode,
          'CommandID' => $CommandID,
          'Amount' => $Amount,
          'Msisdn' => $Msisdn,
          'BillRefNumber' => $BillRefNumber
      ];

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
     * @param string $CommandID - A unique command passed to the M-Pesa system.
     * @param string $IdentifierType -Type of organization receiving the transaction
     * @param string $Remarks - Comments that are sent along with the transaction.
     * @return array
     */
    public function balance( $CommandID, $IdentifierType, $Remarks = "" )
    {
      $url = ( $this -> live === true ) ? 'https://api.safaricom.co.ke/mpesa/accountbalance/v1/query' : 'https://sandbox.safaricom.co.ke/mpesa/accountbalance/v1/query';
      $token = ( $this -> live === true ) ? $this -> liveToken() : $this -> sandBoxToken();

      $curl = curl_init();
      curl_setopt( $curl, CURLOPT_URL, $url);
      curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Content-Type:application/json','Authorization:Bearer '.$token ] ); 

      $curl_post_data = [
          'CommandID' => $CommandID,
          'Initiator' => $this -> username,
          'SecurityCredential' => $this -> securityCredential(),
          'PartyA' => $this -> shortcode,
          'IdentifierType' => $IdentifierType,
          'Remarks' => $Remarks,
          'QueueTimeOutURL' => $this -> timeout_url,
          'ResultURL' => $this -> result_url
      ];

      $data_string = json_encode( $curl_post_data );

      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $curl, CURLOPT_POST, true );
      curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
      curl_setopt( $curl, CURLOPT_HEADER, false );
      $curl_response = curl_exec( $curl );

      return json_decode( $curl_response );
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
    public function status( $TransactionID, $Initiator = null, $IdentifierType = null, $CommandID = "TransactionStatusQuery", $Remarks = "Transaction Status Query", $Occasion = "" )
    {
      $url = ( $this -> live === true ) ? 'https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query' : 'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query';
      $token = ( $this -> live === true ) ? $this -> liveToken() : $this -> sandBoxToken();

      $curl = curl_init();
      curl_setopt( $curl, CURLOPT_URL, $url);
      curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Content-Type:application/json','Authorization:Bearer '.$token ] );

      $Initiator = is_null( $Initiator ) ? $this -> username : $Initiator;
      $IdentifierType = is_null( $IdentifierType ) ? $this -> type : $IdentifierType;

      $curl_post_data = [
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
      ];

      $data_string = json_encode( $curl_post_data );

      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $curl, CURLOPT_POST, true );
      curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
      curl_setopt( $curl, CURLOPT_HEADER, false );

      $curl_response = curl_exec( $curl );
      return json_encode( $curl_response );
    }

    /**
     * Use this function to initiate an STKPush Simulation
     * @param $BusinessShortCode | The organization shortcode used to receive the transaction.
     * @param $LipaNaMpesaPasskey | The password for encrypting the request. This is generated by base64 encoding BusinessShortcode, Passkey and Timestamp.
     * @param $TransactionType | The transaction type to be used for this request. Only CustomerPayBillOnline is supported.
     * @param $Amount | The amount to be transacted.
     * @param $PartyA | The MSISDN sending the funds.
     * @param $PartyB | The organization shortcode receiving the funds
     * @param $PhoneNumber | The MSISDN sending the funds.
     * @param $CallBackURL | The url to where responses from M-Pesa will be sent to.
     * @param $AccountReference | Used with M-Pesa PayBills.
     * @param $TransactionDesc | A description of the transaction.
     * @param $Remark | Remarks
     * @return mixed|string
     */
    public function simulate( $TransactionType, $Amount, $PartyB, $PhoneNumber, $AccountReference, $TransactionDesc, $Remark ){
      $url = ( $this -> live === true ) ? 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest' : 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
      $token = ( $this -> live === true ) ? $this -> liveToken() : $this -> sandBoxToken();

        $timestamp = date( 'YmdHis' );
        $password = base64_encode( $this -> shortcode.$this -> password.$timestamp );

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Content-Type:application/json','Authorization:Bearer '.$token ] );


        $curl_post_data = [
            'BusinessShortCode' => $this -> shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => $TransactionType,
            'Amount' => $Amount,
            'PartyA' => $this -> username,
            'PartyB' => $PartyB,
            'PhoneNumber' => $PhoneNumber,
            'CallBackURL' => $this -> callback_url,
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionType,
            'Remark'=> $Remark
        ];

        $data_string = json_encode( $curl_post_data );

        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
        curl_setopt( $curl, CURLOPT_HEADER, false );

        $curl_response = curl_exec( $curl );
        return $curl_response;
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
      $url = ( $this -> live === true ) ? 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest' : 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
      $token = ( $this -> live === true ) ? $this -> liveToken() : $this -> sandBoxToken();

      $timestamp = date("YmdHis");
      $password = base64_encode( $this -> shortcode.$this -> password.$timestamp );

      $curl = curl_init();
      curl_setopt( $curl, CURLOPT_URL, $url);
      curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Content-Type:application/json','Authorization:Bearer '.$token ] );


      $curl_post_data = [
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
      ];

      $data_string = json_encode( $curl_post_data );

      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $curl, CURLOPT_POST, true );
      curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
      curl_setopt( $curl, CURLOPT_HEADER, false );

      $curl_response = curl_exec( $curl );
      return json_decode( $curl_response );
    }

    /**
     * Use this function to initiate an STKPush Status Query request.
     * @param $checkoutRequestID - Checkout RequestID
     * @return mixed-string
     */
    public function stkpush( $checkoutRequestID )
    {
      $url = ( $this -> live === true ) ? 'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query' : 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
      $token = ( $this -> live === true ) ? $this -> liveToken() : $this -> sandBoxToken();

      $curl = curl_init();
      curl_setopt( $curl, CURLOPT_URL, $url );
      curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Content-Type:application/json','Authorization:Bearer '.$token ] );
      $timestamp = date( "YmdHis" );
      $password = base64_encode( $this -> shortcode.$this -> password.$timestamp );

      $curl_post_data = [
          'BusinessShortCode' => $this -> shortcode,
          'Password' => $password,
          'Timestamp' => $timestamp,
          'CheckoutRequestID' => $checkoutRequestID
      ];

      $data_string = json_encode( $curl_post_data );

      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $curl, CURLOPT_POST, true );
      curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
      curl_setopt( $curl, CURLOPT_HEADER, false );

      $curl_response = curl_exec( $curl );
      return json_decode( $curl_response );
    }

    public function finish()
    {
        header( "Content-Type: application/json" );
        echo( json_encode( [ 'ResponseCode' => 0, 'ResponseDesc' => 'Transaction Accepted Successfully' ] ) );
    }

    public function reject()
    {
      header( "Content-Type: application/json" );
      echo( json_encode( [ 'ResponseCode' => 0, 'ResponseDesc' => 'Transaction Rejected' ] ) );
    }
}