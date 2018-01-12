                                       _
		______   ____    _________| |   _______	   _______      _______	   ______
		|   _ \_/_   | 	|           |  |   __  \   |   ____|  /   ____|   /  __  \
		|  | |  | |  | 	|           |  |  |  |  |  |  |       |  |       |  |  |
		|  | |  | |  | 	| /\	/ \ |  |  |__|  |  |  |____   |  |____   |  |__|  |
		|  | |  | |  | 	|/  \  /   \|  |   ____/   |   ____|  |____   |  |   __   |
		|  | |  | |  | 	|\   \/	   /|  |  |        |  |            |  |  |  |  |  |
		|  | |  | |  | 	| \	  / |  |  |        |  |____    ____|  |  |  |  |  |
		|__| |__| |__|	|  \____/   |  |__|        |_______|  |_______/  |__|  |__|
				|	    |
				|           |
				|___________|
						
# ModoPesa MPESA - PHP
A simple and concise library for integrating MPESA into Websites/Web Apps written in Vanilla PHP.
Based on Daraja - the new <a href="https://developer.safaricom.co.ke/docs/">MPESA API</a>

## Installation
Getting started with this MPESA library is very easy.
* Your site/app MUST be running over https for the MPESA Instant Payment Notification (IPN) to work.
* If you have not created a Daraja app, head over to <a href="https://developer.safaricom.co.ke/docs/#creating-a-sandbox-app">Daraja</a> and create one.

### Install Manually

* These two files are all you need in your app/website for the integration:

	* MPESA.php
	* Response.php

* You can download a zip of all files ( including the license and this README file ) from <a href="https://github.com/ModoPesa/mpesa-php/archive/v0.17.12.23.zip">here</a> and extract it somewhere/anywhere in your app directory. Make sure all files are extracted in the same directory.

### Install Via Composer
Edit your composer.json file to include the following:

```
    {
       "require": {
           "mauko/mpesa": "dev-master"
       }
    }
```

Run `composer install` to pull the latest version of this library.

## Usage
Define some basic constants, either in your app/website's configuration or at the top of the script including the MPESA Class file like so:

Identifiers are as follows:
	1 -> Shortcode
	2 -> Till Number
	4 -> MSISDN

	<?php
	$config = [];
	$config['live'] = "yes|no";
	$config['name'] = 'Your Awesome Business'; 
	$config['shortcode'] = '123456';
	$config['type'] = 1(MSISDN)|2(Till Number)|4(Shortcode); 
	$config['key'] = 'Daraja App Consumer Key';
	$config['secret'] = 'Daraja App Consumer Secret'; 
	$config['username'] = 'Your MPESA Web Portal Username'; 
	$config['password'] = 'Your MPESA Web Portal Password'; 
	$config['passkey'] = 'MPESA Online Pass Key';
	$config['callback_url'] = 'https://yoursite.tld/mpesa/callback/';
	$config['timeout_url'] = 'https://yoursite.tld/mpesa/timeout/';
	$config['result_url'] = 'https://yoursite.tld/mpesa/result/';
	$config['confirmation_url'] = 'https://yoursite.tld/mpesa/confirm/';
	$config['validation_url'] = 'https://yoursite.tld/mpesa/validate/';

Endpoints should be properly validated to make sure that they contain the port, URI and domain name or publicly available IP.

Once all constants have been set, you can now load and instantiate the MPESA object like so:

	<?php
	use Safaricom
	require_once( 'src/MPESA.php' );

	$mpesa = new MPESA( $config );

### Application Programming Interfaces ( APIs )
#### Customer To Business(C2B) Transactions
	<?php
	$mpesa -> c2b( 
		$Amount, 
		$PhoneNumber, 
		$BillReferenceNumber, 
		$CommandID 
	);

The last two arguments are optional. The `$BillReferenceNumber` defaults to a random 6-digit number while `$CommandID` defaults to "CustomerPayBillOnline"

#### Online ( Customer ) Checkout
	<?php
	$mpesa -> checkout( 
		$Amount, 
		$PhoneNumber, 
		$AccountReference, 
		$TransactionDescription, 
		$ExtraRemarks
	);

The last three arguments are optional.

#### Business To Business(B2B) Transactions
	<?php
	$mpesa -> b2b( 
		$Amount, 
		$ReceivingPartyShortcode, 
		$AccountReference, 
		$commandID,
		$RecieverIdentifierType 
		$ExtraRemarks, 
	);

#### Business To Customer(B2C) Transactions
	<?php
	$mpesa -> b2c(
		$Amount, 
		$ReceivingPartyShortcode
		$CommandID,
		$Occasion 
		$Remarks, 
	);

#### Account Balance Check
	<?php
	$mpesa -> balance( 
		$CommandID,
		$Remarks 
	);

Both arguments are optional. `$CommandID` defaults to "AccountBalanceRequest" while `$Remarks` defaults to "Account Balance Request"

#### Transaction Status Check
	<?php
	$mpesa -> status( 
		$TransactionID, 
		$CommandID, 
		$IdentifierType, 
		$Remarks, 
		$Occasion 
	);

The last three arguments are optional `$CommandID` defaults to "TransactionStatusQuery", `$Remarks` defaults to "Transaction Status Query" and `$Occasion` to ""

#### Transaction Reversal
	<?php
	$mpesa -> reverse( 
		$TransactionID, 
		$Amount, 
		$ReceiverParty, 
		$RecieverIdentifierType,
		$Occasion, 
		$Remarks
	);

### Response Processing
The response utility class `Safaricom\Response()` will handle all responses from Safaricom MPESA sent to your endpoints and return the parameters as its properties. Make sure the response utility class is loaded i.e include the Response.php file and instantiate the response object at your endpoint like so:

	<?php
	require_once( 'src/Response.php' );
	$response = new \Safaricom\Response( $type );

where `$type` is the kind of request for whose response to listen for.

Your `$response` object will hold, as properties, all the MPESA response parameters, which you can retrieve like so:

	<?php
	$amount = $response -> Amount;
	$phone = $response -> Phone;

### Validating/Confirming Transactions
Whenever M-Pesa receives a transaction your shortcode, it triggers a validation request against your validation URL and your app/website needs to respond to M-Pesa with a validation response (either a success or an error code). M-Pesa completes or cancels the transaction depending on the validation response it receives from the 3rd party system.  A confirmation request of the transaction is then sent by M-Pesa through your confirmation URL back to which you should respond, acknowledging the confirmation.

To handle validation/confirmation, instantiate the Response object like so:

	<?php
	$response = new \Safaricom\Response( 'validation' );

Or;

	<?php
	$response = new \Safaricom\Response( 'confirmation' );
	
You can then check against all posted values ( e.g if `$response -> Amount` is the correct/expected amount ) and allow the transaction to proceed like so:

	<?php
	$mpesa -> proceed();

Or reject it like so :

	<?php
	$mpesa -> reject();

Even if you do not wish to validate/confirm your transactions, you still need to call `$mpesa -> finish();` at your validation/confirmation endpoints, so MPESA is notified to process the transaction.

## Acknowledgements
* Safaricom, Safaricom Logo, MPESA and the MPESA Logo are registered trademarks of <a href="https://safaricom.co.ke">Safaricom Ltd</a>

## Contributors
* <a href="https://mauko.co.ke/">Mauko</a>
* <a href="#">Muga</a>
* <a href="#">Mecha</a>
* <a href="#">Chenja</a>

## Licensing
This project is released under the MIT License( LICENSE )