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

* Download the zip from here aand extract it in your app.

## Usage
Define some basic constantsto be used by the MPESA Class file like so:

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

Or, is you are testing in a sandbox environment, like so:

	`$mpesa = new \Safaricom\MPESA(false);`

### Customer To Business - C2B Transactions
	`$mpesa -> c2b( $amount, $phone, $billref );`

### Business To Business - B2B Transactions
	`$mpesa -> b2b( $amount, $phone, $billref );`

### Business To Customer - B2C Transactions
	`$mpesa -> b2c( $amount, $phone, $billref );`

To get responses, just call the response class at your endpoints. This utility class will handle responses from Safaricom MPESA and return them as it's properties.

	`$response = new \Safaricom\Response();

	$amount = $response -> Amount;
	$phone = $response -> Phone;
	You can get all other information as above`

## Acknowledgements
* MPESA and the MPESA Logo are registered trademarks of Safaricom Ltd