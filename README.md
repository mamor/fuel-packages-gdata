# FuelPHP Package for Gdata

This package depend on google-api-php-client.  
https://code.google.com/p/google-api-php-client/

***

## Install
### Setup to fuel/packages/gdata
* Use composer https://packagist.org/packages/mp-php/fuel-packages-gdata
* git submodule
* Download zip

### Configuration

##### One
In app/config/config.php

	'always_load' => array('packages' => array(
		'gdata',
		...

or in your code

	Package::load('gdata');

##### Two
Copy packages/gdata/config/gdata.php to under app/config directory and edit

### Usage

	$gdata = Gdata::forge(
		$service_name,
		$instance_name = 'default',
		$config = array()
	);

##### You can access $gdata->client and $gdata->service.
