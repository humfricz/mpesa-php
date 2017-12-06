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
Based on Daraja - the new MPESA API - https://developer.safaricom.co.ke/docs/

## Installation
Getting started with this MPESA library is very easy.
* Your site/app MUST be running over https for the MPESA Instant Payment Notification (IPN) to work.
* If you have not created a Daraja app, head over to https://developer.safaricom.co.ke/docs/#creating-a-sandbox-app and create one.
* These three files are all you need in your app/website for the integration:

	* MPESA.php
	* Response.php
	* cert.cr

* You can download a zip of all file (including the license and this README file) from https://github.com/ModoPesa/mpesa-php/archive/v0.17.12.07.zip and extract it somewhere/anywhere in your app directory. Make sure all three files are in the same directory.

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
	define( 'MPESA_TIMEOUT_URL', 'https://yoursite.tld/timeout/' );
	define( 'MPESA_RESULT_URL', 'https://yoursite.tld/mpesa/' );
	define( 'MPESA_CONFIRMATION_URL', 'https://yoursite.tld/confirm' );
	define( 'MPESA_VALIDATION_URL', 'https://yoursite.tld/validate' );

Load the class...

	<?php
	require_once( 'MPESA.php');

Then instantiate the MPESA object like so:

	<?php
	$mpesa = new \Safaricom\MPESA();

Or, if you are not live yet or you are testing in a sandbox environment, pass `false` as an argument when instantiating the MPESA object, like so:

	<?php
	$mpesa = new \Safaricom\MPESA(false);

### Customer To Business(C2B) Transactions
	<?php
	$mpesa -> c2b( 
		$Amount, 
		$Msisdn, 
		$BillRefNumber, 
		$CommandID 
		);

The last two arguments are optional. The `$BillRefNumber` defaults to nothing (`""`) while `$CommandID` defaults to "CustomerPayBillOnline"

### Business To Business(B2B) Transactions
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

### Business To Customer(B2C) Transactions
	<?php
	$mpesa -> b2c( 
		$CommandID, 
		$Amount, 
		$PartyB, 
		$Remarks, 
		$Occasion 
		);

### Check Account Balance
	<?php
	$mpesa -> balance( 
		$CommandID, 
		$IdentifierType, 
		$Remarks 
		);

The `$Remarks` are optional.

### Check Transaction Status
	<?php
	$mpesa -> status( 
		$CommandID, 
		$TransactionID, 
		$IdentifierType, 
		$Remarks, 
		$Occasion 
		);

### Transaction Reversal
	<?php
	$mpesa -> reverse( 
		$TransactionID, 
		$Amount, 
		$ReceiverParty, 
		$RecieverIdentifierType, 
		$Remarks, 
		$Occasion 
		);

### Process Responses
This response utility class `Safaricom\Response($type)` will handle responses from Safaricom MPESA sent to your endpoints and return the parameters as its properties. Just use this code at your endpoint, where `$type` is the kind of request for whose response to listen for. 

	<?php
	$response = new \Safaricom\Response($type);

Your `$response` object will hold, as properties, all the parameters, which you can retrieve like so:

	<?php
	$amount = $response -> Amount;
	$phone = $response -> Phone;

### Validating/Confirming Transactions
	<?php
	$response = new \Safaricom\Response('validation');
	
You can then check against all posted values (e.g if `$response -> Amount` is correct/expected amount) and validate like so:

	<?php
	$mpesa -> finish();

Or reject like so :

	<?php
	$mpesa -> finish(false);

Even if you do not wish to validate/confirm your transactions, you still need to call `$mpesa -> finish();` at your validation/confirmation endpoints, so MPESA is notified to process the transaction.

## Acknowledgements
* MPESA and the MPESA Logo are registered trademarks of Safaricom Ltd - https://safaricom.co.ke