<?php
/**
* @package ModoPesa M-Pesa - PHP
* @subpackage M-Pesa Response Utility Class
* @version 0.18.01
* @author Mauko Maunde < hi@mauko.co.ke >
* @link https://developer.safaricom.co.ke/docs
**/
namespace Safaricom;

/**
 * Safaricom M-Pesa Response Data object utility class
 * @see https://developer.safaricom.co.ke/docs
 */
class Response
{
    private $response;
    
    public $responseCode;
    public $responseDescription;

    public $reasonType;

    public $phoneNumber;
    public $businessShortCode;
    public $debitAccountType;

    public $receiptNo;
    public $billRefNumber;
    public $invoiceNumber;
    public $mpesaReceiptNumber;

    public $amount;
    public $balance;
    public $accountBalance;
    public $orgAccountBalance;
    public $debitAccountCurrentBalance;
    public $debitPartyAffectedAccountBalance;
    public $initiatorAccountCurrentBalance;

    public $workingAccountAvailableFunds;
    public $utilityAccountAvailableFunds;
    public $chargesPaidAccountAvailableFunds;

    public $firstName;
    public $middleName;
    public $lastName;
    public $creditPartyName;
    public $debitPartyName;

    public $resultType;
    public $resultCode;
    public $resultDesc;

    public $transactionID;
    public $transactionReceipt;
    public $thirdPartyTransID;
    public $conversationID;
    public $originatorConversationID;
    public $merchantRequestID;
    public $checkoutRequestID;

    public $transTime;
    public $initiatedTime;
    public $finalisedTime;
    public $boCompletedTime;
    public $transactionDate;
    public $transCompletedTime;
    public $transactionCompletedDateTime;

    public $transactionType;
    public $transactionStatus;
    public $transactionReason;

    public $debitPartyCharges;
    
    public $receiverPartyPublicName;

    public $recipientIsRegisteredCustomer;

    public $currency;

    public function __construct( $type )
    {
        $this -> response = json_decode( file_get_contents('php://input') );
        call_user_func_array( [ $this, $type ], [] );
    }
    /**
     * Sets the response parameters for the B2B request callback
     */
    public function b2b(){
        $this -> resultCode = $this -> response ->  Result -> ResultCode;
        $this -> resultDesc = $this -> response ->  Result -> ResultDesc;
        $this -> originatorConversationID = $this -> response ->  Result -> OriginatorConversationID;
        $this -> conversationID = $this -> response ->  Result -> ConversationID;
        $this -> transactionID = $this -> response ->  Result -> TransactionID;
        $this -> transactionReceipt = $this -> response ->  Result -> ResultParameters -> ResultParameter[0] -> Value;
        $this -> transactionAmount = $this -> response ->  Result -> ResultParameters -> ResultParameter[1] -> Value;
        $this -> workingAccountAvailableFunds = $this -> response ->  Result -> ResultParameters -> ResultParameter[2] -> Value;
        $this -> utilityAccountAvailableFunds = $this -> response ->  Result -> ResultParameters -> ResultParameter[3] -> Value;
        $this -> transactionCompletedDateTime = $this -> response ->  Result -> ResultParameters -> ResultParameter[4] -> Value;
        $this -> receiverPartyPublicName = $this -> response ->  Result -> ResultParameters -> ResultParameter[5] -> Value;
        $this -> chargesPaidAccountAvailableFunds = $this -> response ->  Result -> ResultParameters -> ResultParameter[6] -> Value;
        $this -> recipientIsRegisteredCustomer = $this -> response ->  Result -> ResultParameters -> ResultParameter[7] -> Value;
    }
    /**
     * Sets the response parameters for the B2C request callback
     */
    public function b2c()
    {
        $this -> resultCode = $this -> response ->  Result -> ResultCode;
        $this -> resultDesc = $this -> response ->  Result -> ResultDesc;
        $this -> originatorConversationID = $this -> response ->  Result -> OriginatorConversationID;
        $this -> conversationID = $this -> response ->  Result -> ConversationID;
        $this -> transactionID = $this -> response ->  Result -> TransactionID;
        $this -> initiatorAccountCurrentBalance = $this -> response ->  Result -> ResultParameters -> ResultParameter[0] -> Value;
        $this -> debitAccountCurrentBalance = $this -> response ->  Result -> ResultParameters -> ResultParameter[1] -> Value;
        $this -> amount = $this -> response ->  Result -> ResultParameters -> ResultParameter[2] -> Value;
        $this -> debitPartyAffectedAccountBalance = $this -> response ->  Result -> ResultParameters -> ResultParameter[3] -> Value;
        $this -> transCompletedTime = $this -> response ->  Result -> ResultParameters -> ResultParameter[4] -> Value;
        $this -> debitPartyCharges = $this -> response ->  Result -> ResultParameters -> ResultParameter[5] -> Value;
        $this -> receiverPartyPublicName = $this -> response ->  Result -> ResultParameters -> ResultParameter[6] -> Value;
        $this -> currency = $this -> response ->  Result -> ResultParameters -> ResultParameter[7] -> Value;
    }

    /**
     * Sets the response parameters for the C2B Validation request callback
     */
    public function c2b()
    {
        $this -> transactionType = $this -> response ->  TransactionType;
        $this -> transID = $this -> response ->  TransID;
        $this -> transTime = $this -> response ->  TransTime;
        $this -> amount = $this -> response ->  TransAmount;
        $this -> businessShortCode = $this -> response ->  BusinessShortCode;
        $this -> billRefNumber = $this -> response ->  BillRefNumber;
        $this -> invoiceNumber = $this -> response ->  InvoiceNumber;
        $this -> orgAccountBalance = $this -> response ->  OrgAccountBalance;
        $this -> thirdPartyTransID = $this -> response ->  ThirdPartyTransID;
        $this -> phoneNumber = $this -> response ->  MSISDN;
        $this -> firstName = $this -> response ->  FirstName;
        $this -> middleName = $this -> response ->  MiddleName;
        $this -> lastName = $this -> response ->  LastName;
    }

    /**
     * Sets the response parameters for the C2B Confirmation result callback
     */
    public function confirmation()
    {
        $this -> transactionType = $this -> response ->  TransactionType;
        $this -> transID = $this -> response ->  TransID;
        $this -> transTime = $this -> response ->  TransTime;
        $this -> transAmount = $this -> response ->  TransAmount;
        $this -> businessShortCode = $this -> response ->  BusinessShortCode;
        $this -> billRefNumber = $this -> response ->  BillRefNumber;
        $this -> invoiceNumber = $this -> response ->  InvoiceNumber;
        $this -> orgAccountBalance = $this -> response ->  OrgAccountBalance;
        $this -> thirdPartyTransID = $this -> response ->  ThirdPartyTransID;
        $this -> phoneNumber = $this -> response ->  MSISDN;
        $this -> firstName = $this -> response ->  FirstName;
        $this -> middleName = $this -> response ->  MiddleName;
        $this -> lastName = $this -> response ->  LastName;
    }

    /**
     * Sets the response parameters for the Account Balance request callback
     */
    public function balance()
    {
        $this -> resultType = $this -> response ->  Result -> ResultType;
        $this -> resultCode = $this -> response ->  Result -> ResultCode;
        $this -> resultDesc = $this -> response ->  Result -> ResultDesc;
        $this -> originatorConversationID = $this -> response ->  Result -> OriginatorConversationID;
        $this -> conversationID = $this -> response ->  Result -> ConversationID;
        $this -> transactionID = $this -> response ->  Result -> TransactionID;
        $this -> accountBalance = $this -> response ->  Result -> ResultParameters -> ResultParameter[0] -> Value;
        $this -> boCompletedTime = $this -> response ->  Result -> ResultParameters -> ResultParameter[1] -> Value;
    }

    /**
     * Sets the response parameters for the Reversal request callback
     */
    public function reversal()
    {
        $this -> resultType = $this -> response ->  Result -> ResultType;
        $this -> resultCode = $this -> response ->  Result -> ResultCode;
        $this -> resultDesc = $this -> response ->  Result -> ResultDesc;
        $this -> originatorConversationID = $this -> response ->  Result -> OriginatorConversationID;
        $this -> conversationID = $this -> response ->  Result -> ConversationID;
        $this -> transactionID = $this -> response ->  Result -> TransactionID;
    }

    /**
     * Sets the response parameters for the STK push request callback
     */
    public function checkout()
    {
        $this -> resultCode = $this -> response ->  stkCallback -> ResultCode;
        $this -> resultDesc = $this -> response ->  stkCallback -> ResultDesc;
        $this -> merchantRequestID = $this -> response ->  stkCallback -> MerchantRequestID;
        $this -> checkoutRequestID = $this -> response ->  stkCallback -> CheckoutRequestID;
        $this -> amount = $this -> response ->  stkCallback -> CallbackMetadata -> Item[0] -> Value;
        $this -> mpesaReceiptNumber = $this -> response ->  stkCallback -> CallbackMetadata -> Item[1] -> Value;
        $this -> balance = $this -> response ->  stkCallback -> CallbackMetadata -> Item[2] -> Value;
        $this -> utilityAccountAvailableFunds = $this -> response ->  stkCallback -> CallbackMetadata -> Item[3] -> Value;
        $this -> transactionDate = $this -> response ->  stkCallback -> CallbackMetadata -> Item[4] -> Value;
        $this -> phoneNumber = $this -> response ->  stkCallback -> CallbackMetadata -> Item[5] -> Value;
    }

    /**
     * Sets the response parameters for the STK Push  request callback
     */
    public function stkpush()
    {
        $this -> responseCode = $this -> response ->  ResponseCode;
        $this -> responseDescription = $this -> response ->  ResponseDescription;
        $this -> merchantRequestID = $this -> response ->  MerchantRequestID;
        $this -> checkoutRequestID = $this -> response ->  CheckoutRequestID;
        $this -> resultCode = $this -> response ->  ResultCode;
        $this -> resultDesc = $this -> response ->  ResultDesc;
    }

    /**
     * Sets the response parameters for the Transaction status request callback
     */
    public function status()
    {
        $this -> resultCode = $this -> response ->  Result -> ResultCode;
        $this -> resultDesc = $this -> response ->  Result -> ResultDesc;
        $this -> originatorConversationID = $this -> response ->  Result -> OriginatorConversationID;
        $this -> conversationID = $this -> response ->  Result -> ConversationID;
        $this -> transactionID = $this -> response ->  Result -> TransactionID;
        $this -> receiptNo = $this -> response ->  Result -> ResultParameters -> ResultParameter[0] -> Value;
        $this -> conversationID = $this -> response ->  Result -> ResultParameters -> ResultParameter[1] -> Value;
        $this -> finalisedTime = $this -> response ->  Result -> ResultParameters -> ResultParameter[2] -> Value;
        $this -> amount = $this -> response ->  Result -> ResultParameters -> ResultParameter[3] -> Value;
        $this -> transactionStatus = $this -> response ->  Result -> ResultParameters -> ResultParameter[4] -> Value;
        $this -> reasonType = $this -> response ->  Result -> ResultParameters -> ResultParameter[5] -> Value;
        $this -> transactionReason = $this -> response ->  Result -> ResultParameters -> ResultParameter[6] -> Value;
        $this -> debitPartyCharges = $this -> response ->  Result -> ResultParameters -> ResultParameter[7] -> Value;
        $this -> debitAccountType = $this -> response ->  Result -> ResultParameters -> ResultParameter[8] -> Value;
        $this -> initiatedTime = $this -> response ->  Result -> ResultParameters -> ResultParameter[9] -> Value;
        $this -> originatorConversationID = $this -> response ->  Result -> ResultParameters -> ResultParameter[10] -> Value;
        $this -> creditPartyName = $this -> response ->  Result -> ResultParameters -> ResultParameter[11] -> Value;
        $this -> debitPartyName = $this -> response ->  Result -> ResultParameters -> ResultParameter[12] -> Value;
    }

}