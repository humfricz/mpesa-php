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
* These two files are all you need in your app/website for the integration:

	* MPESA.php
	* Response.php

* You can download a zip of all files ( including the license and this README file ) from <a href="https://github.com/ModoPesa/mpesa-php/archive/v0.17.12.07.zip">here</a> and extract it somewhere/anywhere in your app directory. Make sure all files are extracted in the same directory.

## Usage
Define some basic constants, either in your app/website's configuration or at the top of the script including the MPESA Class file like so:

	<?php
	define( 'MPESA_NAME', 'Your Awesome Business' );
	define( 'MPESA_SHORTCODE', '123456' );
	define( 'MPESA_ID_TYPE', 'MSISDN|Till Number|Shortcode' );
	define( 'MPESA_KEY', 'Daraja App Key' );
	define( 'MPESA_SECRET', 'Daraja App Secret' );
	define( 'MPESA_USERNAME', 'Your MPESA Web Portal Username' );
	define( 'MPESA_PASSWORD', 'Your MPESA Web Portal Password' );
	define( 'MPESA_CALLBACK_URL', 'https://yoursite.tld/mpesa/callback/' );
	define( 'MPESA_TIMEOUT_URL', 'https://yoursite.tld/mpesa/timeout/' );
	define( 'MPESA_RESULT_URL', 'https://yoursite.tld/mpesa/result/' );
	define( 'MPESA_CONFIRMATION_URL', 'https://yoursite.tld/mpesa/confirm/' );
	define( 'MPESA_VALIDATION_URL', 'https://yoursite.tld/mpesa/validate/' );

For PHP 7+ users, you could use something like this instead:

	<?php
	define ( 
		'MPESA_CONFIG', 
		[
			'NAME' => 'Your Awesome Business', 
			'SHORTCODE' => '123456', 
			'ID_TYPE' => 'MSISDN|Till Number|Shortcode', 
			'KEY' => 'Daraja App Key', 
			'SECRET' => 'Daraja App Secret', 
			'USERNAME' => 'Your MPESA Web Portal Username', 
			'PASSWORD' => 'Your MPESA Web Portal Password', 
			'CALLBACK_URL' => 'https://yoursite.tld/mpesa/callback/', 
			'TIMEOUT_URL' => 'https://yoursite.tld/mpesa/timeout/', 
			'RESULT_URL' => 'https://yoursite.tld/mpesa/result/', 
			'CONFIRMATION_URL' => 'https://yoursite.tld/mpesa/confirm/', 
			'VALIDATION_URL' => 'https://yoursite.tld/mpesa/validate/' 
		] 
	);

Endpoints should be properly validated to make sure that they contain the port, URI and domain name or publicly available IP.

Once all constants have been set, you can now load and instantiate the MPESA object like so:

	<?php
	require_once( 'MPESA.php' );
	$mpesa = new \Safaricom\MPESA();

Or, if you are not live yet or you are testing in a sandbox environment, pass `false` and the test credentials public key `$publickey` as arguments when instantiating the MPESA object, like so:

	<?php
	$mpesa = new \Safaricom\MPESA( false, $publickey );

### Application Programming Interfaces ( APIs )
#### Customer To Business(C2B) Transactions
	<?php
	$mpesa -> c2b( 
		$Amount, 
		$Msisdn, 
		$BillRefNumber, 
		$CommandID 
	);

The last two arguments are optional. The `$BillRefNumber` defaults to a random 6-digit number while `$CommandID` defaults to "CustomerPayBillOnline"

#### Business To Business(B2B) Transactions
	<?php
	$mpesa -> b2b( 
		$Amount, 
		$PartyB, 
		$Remarks, 
		$AccountReference, 
		$commandID, 
		$SenderIdentifierType, 
		$RecieverIdentifierType 
	);

#### Business To Customer(B2C) Transactions
	<?php
	$mpesa -> b2c( 
		$CommandID, 
		$Amount, 
		$PartyB, 
		$Remarks, 
		$Occasion 
	);

#### Account Balance Check
	<?php
	$mpesa -> balance( 
		$CommandID, 
		$IdentifierType, 
		$Remarks 
	);

The `$Remarks` are optional.

#### Transaction Status Check
	<?php
	$mpesa -> status( 
		$CommandID, 
		$TransactionID, 
		$IdentifierType, 
		$Remarks, 
		$Occasion 
	);

#### Transaction Reversal
	<?php
	$mpesa -> reverse( 
		$TransactionID, 
		$Amount, 
		$ReceiverParty, 
		$RecieverIdentifierType, 
		$Remarks, 
		$Occasion 
	);

### Response Processing
The response utility class `Safaricom\Response()` will handle all responses from Safaricom MPESA sent to your endpoints and return the parameters as its properties. Make sure the response utility class is loaded i.e include the Response.php file `require_once( 'path/to/Response.php' )`. Instantiate the response object at your endpoint like so:

	<?php
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
	$mpesa -> finish();

Or reject it like so :

	<?php
	$mpesa -> reject();

Even if you do not wish to validate/confirm your transactions, you still need to call `$mpesa -> finish();` at your validation/confirmation endpoints, so MPESA is notified to process the transaction.

## Acknowledgements
* Safaricom, Safaricom Logo, MPESA and the MPESA Logo are registered trademarks of <a href="https://safaricom.co.ke">Safaricom Ltd</a>

## Contributors
* <a href="https://mauko.co.ke/">Mauko</a>
* <a href="">Muga</a>
* <a href="">Mecha</a>
* <a href="">Chenja</a>

## Licensing
This project is released under the MIT License( LICENSE )