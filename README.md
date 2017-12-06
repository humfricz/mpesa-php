                                           _
		______   ____    _________| |   _______	   _______      _______	   ______
		|   _ \_/_   | 	|           |  |   __  \   |   ____|  /   ____|   /  __  \
		|  | |  | |  | 	|           |  |  |  |  |  |       |  |       |  |  |  |
		|  | |  | |  | 	| /\	/ \ |  |  |__|  |  |  |____   |  |____   |  |__|  |
		|  | |  | |  | 	|/  \  /   \|  |   ____/   |   ____|  |____   |  |   __   |
		|  | |  | |  | 	|\   \/	   /|  |  |        |  |            |  |  |  |  |  |
		|  | |  | |  | 	| \	  / |  |  |        |  |____    ____|  |  |  |  |  |
		|__| |__| |__|	|  \____/   |  |__|        |_______|  |_______/  |__|  |__|
				|	    |
				|           |
				|___________|
						
# ModoPesa for MPESA
A set of Libraries for integrating MPESA into Websites/Web Apps written in Vanilla PHP.

## Installation
Getting started with MPESA is very easy.
* Your site/app MUST be running over https for the MPESA Instant Payment Notification (IPN) to work.
* You actually only need these three files for the integration to work:

	* MPESA.php
	* Response.php
	* cert.cr

* You can download a zip of all three from here(https://github.com/ModoPesa/mpesa-php) and extract it somewhere/anywhere in your app directory. Make sure all three files are in the same directory.

## Usage
Define some basic constants required by the MPESA Class file like so:

	define( 'MPESA_NAME', 'Your Awesome Business' );
	define( 'MPESA_SHORTCODE', '123456' );
	define( 'MPESA_KEY', 'bnWPihAdtqRFZiJumUtEfI2lnEmQG09d' );
	define( 'MPESA_SECRET', 'VAdWE9ns8jGoImZW' );
	define( 'MPESA_PASSWORD', 'MTc0Mzc5YmZiMjc5ZjlhYTliZGJjZjE1OGU5N2RkNzFhNDY3Y2QyZTBjODkzMDU5YjEwZjc4ZTZiNzJhZGExZWQyYzkxOTIwMTcxMDA5MTAxOTMy' );
	define( 'MPESA_TIMEOUT_URL', 'https://yoursite.tld/timeout/' );
	define( 'MPESA_RESULT_URL', 'https://yoursite.tld/mpesa/' );
	define( 'MPESA_CONFIRMATION_URL', 'https://yoursite.tld/confirm' );
	define( 'MPESA_VALIDATION_URL', 'https://yoursite.tld/validate' );

Load the class...

	`require_once( 'MPESA.php');`

Then instantiate the MPESA object like so:

	`$mpesa = new \Safaricom\MPESA();`

Or, if you are not live yet or you are testing in a sandbox environment, pass false as an argument when instantiating the MPESA object, like so:

	`$mpesa = new \Safaricom\MPESA(false);`

### Customer To Business(C2B) Transactions
	`$mpesa -> c2b( 
		$Amount, 
		$Msisdn, 
		$BillRefNumber, 
		$CommandID 
		);`

The last two arguments are optional. The `$BillRefNumber` defaults to nothing ("") while `$CommandID` defaults to "CustomerPayBillOnline"

### Business To Business(B2B) Transactions
	`$mpesa -> b2b( 
		$Amount, 
		$PartyB, 
		$Remarks, 
		$AccountReference, 
		$commandID, 
		$SenderIdentifierType, 
		$RecieverIdentifierType 
		);`

### Business To Customer(B2C) Transactions
	`$mpesa -> b2c( 
		$CommandID, 
		$Amount, 
		$PartyB, 
		$Remarks, 
		$Occasion 
		);`

### Check Account Balance
	`$mpesa -> balance( 
		$CommandID, 
		$IdentifierType, 
		$Remarks 
		);`

The `$Remarks` are optional.

### Check Transaction Status
	`$mpesa -> status( 
		$CommandID, 
		$TransactionID, 
		$IdentifierType, 
		$Remarks, 
		$Occasion 
		);`

### Transaction Reversal
	`$mpesa -> reverse( 
		$TransactionID, 
		$Amount, 
		$ReceiverParty, 
		$RecieverIdentifierType, 
		$Remarks, 
		$Occasion 
		);`

To get responses, just call the response class at your endpoints. This utility class will handle responses from Safaricom MPESA and return the parameters as its properties.

	`$response = new \Safaricom\Response($type);`

Where $type is the kind of request for whose response to listen for. Your `$response` object will hold all the parameters, which you can retrieve like so:

	`$amount = $response -> Amount;`
	`$phone = $response -> Phone;`

You can get all other response parameters/information as above.

### Validating/Confirming Transactions
	`$response = new \Safaricom\Response('validation');`
	
You can then check against all posted values, i.e $response -> Amount and validate like so:
	`$mpesa -> finish();`

Or reject like so :
	`$mpesa -> finish(false);`

If you do not wish to validate/confirm your transactions, you still need to call `$mpesa -> finish();` at your validation/confirmation endpoints, so MPESA is notified to process the transaction.

## Acknowledgements
* MPESA and the MPESA Logo are registered trademarks of Safaricom Ltd