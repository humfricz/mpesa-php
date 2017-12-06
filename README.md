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
Define some basic constants like so:

	define( 'MPESA_NAME', $this->get_option( 'business' ) );
	define( 'MPESA_SHORTCODE', $this->get_option( 'shortcode' ) );
	define( 'MPESA_KEY', $this->get_option( 'key' ) );
	define( 'MPESA_SECRET', $this->get_option( 'secret' ) );
	define( 'MPESA_PASSWORD', $this->get_option( 'password' ) );
	define( 'MPESA_TIMEOUT_URL', $url.'/wc-api/woocommerce_api_wc_mpesa_timeout' );
	define( 'MPESA_RESULT_URL', $url.'/wc-api/woocommerce_api_wc_mpesa' );
	define( 'MPESA_CONFIRMATION_URL', $url.'/wc-api/woocommerce_api_wc_mpesa_confirm' );
	define( 'MPESA_VALIDATION_URL', $url.'/wc-api/woocommerce_api_wc_mpesa_validate' );

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