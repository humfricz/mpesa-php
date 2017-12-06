<?php
/**
* @package MPESA Inside
* @version 0.17.11
* @author Mauko Maunde < hi@mauko.co.ke >
* @see https://developer.safaricom.co.ke/docs
**/

namespace Safaricom;

/**
 * Safaricom MPESA Response Data object utility class
 * @link https://developer.safaricom.co.ke/docs
 */
class Response
{
    //general status
    public $ResultCode;
    public $ResultDesc;
    public $ResultType;
    public $OriginatorConversationID;
    public $ConversationID;
    public $TransactionID;
    public $TransactionReceipt;
    public $TransactionAmount;

    //b2c
    public $B2CWorkingAccountAvailableFunds;
    public $B2CUtilityAccountAvailableFunds;
    public $TransactionCompletedDateTime;
    public $B2CChargesPaidAccountAvailableFunds;
    public $B2CRecipientIsRegisteredCustomer;
    public $reference;
    public $referenceitemkey;
    public $referenceitemvalue;

    //b2b
    public $InitiatorAccountCurrentBalance;
    public $DebitAccountCurrentBalance;
    public $Amount;
    public $DebitPartyAffectedAccountBalance;
    public $TransCompletedTime;
    public $DebitPartyCharges;
    public $ReceiverPartyPublicName;
    public $Currency;

    /**
     * Constructor method for our utitlity Safaricom MPESA response processing class
     * @param string $type Type of transaction for which to process response
     */
    public function __construct( $type = "c2b" )
    {
        $rawresponse = file_get_contents( 'php://input' );
        $response = json_decode( $rawresponse, true );
        $result = $response['Result'];

        if ( $type = "reversal" ) {
            $this -> ResultType = $result['ResultType'];
            $this -> ResultCode = $result['ResultCode'];
            $this -> ResultDesc = $result['ResultDesc'];
            $this -> OriginatorConversationID = $result['OriginatorConversationID'];
            $this -> ConversationID = $result['ConversationID'];
            $this -> TransactionID = $result['TransactionID'];
            $this -> QueueTimeoutURL = $result['ReferenceData']['ReferenceItem']['Value'];
        } elseif ( $type = "b2c" ) {
            $this -> ResultType = $result['ResultType'];
            $this -> ResultCode = $result['ResultCode'];
            $this -> ResultDesc = $result['ResultDesc'];
            $this -> OriginatorConversationID = $result['OriginatorConversationID'];
            $this -> ConversationID = $result['ConversationID'];
            $this -> TransactionID = $result['TransactionID'];

            $resultparam = $result['ResultParameters']['ResultParameter'];
            $this -> TransactionReceipt = $resultparam[0]['Value'];
            $this -> TransactionAmount = $resultparam[1]['Value'];
            $this -> B2CWorkingAccountAvailableFunds = $resultparam[2]['Value'];
            $this -> B2CUtilityAccountAvailableFunds = $resultparam[3]['Value'];
            $this -> TransactionCompletedDateTime = $resultparam[4]['Value'];
            $this -> B2CChargesPaidAccountAvailableFunds = $resultparam[5]['Value'];
            $this -> B2CRecipientIsRegisteredCustomer = $resultparam[6]['Value'];
            $this -> referenceitemkey = $result['ReferenceData']['ReferenceItem']['Key'];
            $this -> referenceitemvalue = $result['ReferenceData']['ReferenceItem']['Value'];
        } elseif ( $type = "b2b" ) {
            $this -> ResultType = $result['ResultType'];
            $this -> ResultCode = $result['ResultCode'];
            $this -> ResultDesc = $result['ResultDesc'];
            $this -> OriginatorConversationID = $result['OriginatorConversationID'];
            $this -> ConversationID = $result['ConversationID'];
            $this -> TransactionID = $result['TransactionID'];

            $resultparam = $result['ResultParameters']['ResultParameter'];
            $this -> InitiatorAccountCurrentBalance = $resultparam[0]['Value'];
            $this -> DebitAccountCurrentBalance = $resultparam[1]['Value'];
            $this -> Amount = $resultparam[2]['Value'];
            $this -> DebitPartyAffectedAccountBalance = $resultparam[3]['Value'];
            $this -> TransCompletedTime = $resultparam[4]['Value'];
            $this -> DebitPartyCharges = $resultparam[5]['Value'];
            $this -> ReceiverPartyPublicName = $resultparam[6]['Value'];
            $this -> Currency = $resultparam[7]['Value'];
            $this -> BillReferenceNumber = $result['ReferenceData']['ReferenceItem'][0]['Value'];
            $this -> QueueTimeoutURL = $result['ReferenceData']['ReferenceItem'][1]['Value'];
            $this -> Occassion = $result['ReferenceData']['ReferenceItem'][2]['Value'];
        } elseif ( $type = "c2b" ) {
            $this -> ResultType = $result['ResultType'];
            $this -> ResultCode = $result['ResultCode'];
            $this -> ResultDesc = $result['ResultDesc'];
            $this -> OriginatorConversationID = $result['OriginatorConversationID'];
            $this -> ConversationID = $result['ConversationID'];
            $this -> TransactionID = $result['TransactionID'];

            $resultparam = $result['ResultParameters']['ResultParameter'];
            $this -> TransactionReceipt = $resultparam[0]['Value'];
            $this -> TransactionAmount = $resultparam[1]['Value'];
            $this -> B2CWorkingAccountAvailableFunds = $resultparam[2]['Value'];
            $this -> B2CUtilityAccountAvailableFunds = $resultparam[3]['Value'];
            $this -> TransactionCompletedDateTime = $resultparam[4]['Value'];
            $this -> B2CChargesPaidAccountAvailableFunds = $resultparam[5]['Value'];
            $this -> B2CRecipientIsRegisteredCustomer = $resultparam[6]['Value'];
            $this -> BillReferenceNumber = $result['ReferenceData']['ReferenceItem'][0]['Value'];
            $this -> QueueTimeoutURL = $result['ReferenceData']['ReferenceItem'][1]['Value'];
        } elseif ( $type = "stkpush" ) {
            $this -> ResponseCode = $result['ResponseCode'];
            $this -> ResponseDescription = $result['ResponseDescription'];
            $this -> MerchantRequestID = $result['MerchantRequestID'];
            $this -> CheckoutRequestID = $result['CheckoutRequestID'];
            $this -> ResultCode = $result['ResultCode'];
            $this -> ResultDesc = $result['ResultDesc'];
        } elseif ( $type = "balance" ) {
            $this -> ResultType = $result['ResultType'];
            $this -> ResultCode = $result['ResultCode'];
            $this -> ResultDesc = $result['ResultDesc'];
            $this -> OriginatorConversationID = $result['OriginatorConversationID'];
            $this -> ConversationID = $result['ConversationID'];
            $this -> TransactionID = $result['TransactionID'];
            
            $resultparam = $result['ResultParameters']['ResultParameter'];
            $this -> AccountBalance = $resultparam[0]['Value'];
            $this -> BOCompletedTime = $resultparam[1]['Value'];

            $this -> QueueTimeoutURL = $result['ReferenceData']['ReferenceItem']['Value'];
        } elseif ( $type = "status" ) {
            $this -> ResultType = $result['ResultType'];
            $this -> ResultCode = $result['ResultCode'];
            $this -> ResultDesc = $result['ResultDesc'];
            $this -> OriginatorConversationID = $result['OriginatorConversationID'];
            $this -> ConversationID = $result['ConversationID'];
            $this -> TransactionID = $result['TransactionID'];
            
            $resultparam = $result['ResultParameters']['ResultParameter'];
            $this -> ReceiptNo = $resultparam[0]['Value'];
            $this -> Conversation_ID = $resultparam[1]['Value'];
            $this -> FinalisedTime = $resultparam[2]['Value'];
            $this -> Amount = $resultparam[3]['Value'];
            $this -> TransactionStatus = $resultparam[4]['Value'];
            $this -> ReasonType = $resultparam[5]['Value'];
            $this -> TransactionReason = $resultparam[6]['Value'];
            $this -> DebitPartyCharges = $resultparam[7]['Value'];
            $this -> DebitAccountType = $resultparam[8]['Value'];
            $this -> InitiatedTime = $resultparam[9]['Value'];
            $this -> Originator_Conversation_ID = $resultparam[10]['Value'];
            $this -> CreditPartyName = $resultparam[11]['Value'];
            $this -> DebitPartyName = $resultparam[12]['Value'];

            $this -> Occassion = $result['ReferenceData']['ReferenceItem']['Value'];
        } elseif ( $type = "validation" ) {
            $this -> TransactionType = $result['TransactionType'];
            $this -> TransID = $result['TransID'];
            $this -> TransTime = $result['TransTime'];
            $this -> TransAmount = $result['TransAmount'];
            $this -> BusinessShortCode = $result['BusinessShortCode'];
            $this -> BillRefNumber = $result['BillRefNumber'];
            $this -> InvoiceNumber = $result['InvoiceNumber'];
            $this -> OrgAccountBalance = $result['OrgAccountBalance'];
            $this -> ThirdPartyTransID = $result['ThirdPartyTransID'];
            $this -> MSISDN = $result['MSISDN'];
            $this -> FirstName = $result['FirstName'];
            $this -> MiddleName = $result['MiddleName'];
            $this -> LastName = $result['LastName'];
        } elseif ( $type = "confirmation" ) {
            $this -> TransactionType = $result['TransactionType'];
            $this -> TransID = $result['TransID'];
            $this -> TransTime = $result['TransTime'];
            $this -> TransAmount = $result['TransAmount'];
            $this -> BusinessShortCode = $result['BusinessShortCode'];
            $this -> BillRefNumber = $result['BillRefNumber'];
            $this -> InvoiceNumber = $result['InvoiceNumber'];
            $this -> OrgAccountBalance = $result['OrgAccountBalance'];
            $this -> ThirdPartyTransID = $result['ThirdPartyTransID'];
            $this -> MSISDN = $result['MSISDN'];
            $this -> FirstName = $result['FirstName'];
            $this -> MiddleName = $result['MiddleName'];
            $this -> LastName = $result['LastName'];
        } elseif ( $type = "paybill" ) {
            $result = $response['Body']['stkCallback'];
            $this -> MerchantRequestID = $result['MerchantRequestID'];
            $this -> CheckoutRequestID = $result['CheckoutRequestID'];
            $this -> ResultCode = $result['ResultCode'];
            $this -> ResultDesc = $result['ResultDesc'];

            $metadata = $result['CallbackMetadata']['Item'];
            $this -> Amount = $metadata[0]['Value'];
            $this -> MpesaReceiptNumber = $metadata[1]['Value'];
            $this -> Balance = $metadata[2]['Value'];
            $this -> TransactionDate = $metadata[3]['Value'];
            $this -> PhoneNumber = $metadata[4]['Value'];
        }
    }
}