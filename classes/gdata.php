<?php

namespace Gdata;

class Gdata
{
	protected static $_instance;
	protected static $_instances = array();

	public $client;
	public $service;

	public static function _init()
	{
		\Config::load('gdata', true);
	}


	public function __construct($service, $name, $config)
	{
		require_once PKGPATH."gdata/vendor/google-api-php-client/src/Google_Client.php";

		$service_class = 'Google_'.ucfirst(strtolower($service)).'Service';
		require_once PKGPATH."gdata/vendor/google-api-php-client/src/contrib/{$service_class}.php";

		if( ! class_exists($service_class))
		{
			throw new \FuelException('Could not find Gdata service class: '.$service_class);
		}

		$config = array_merge(\Config::get('gdata'), $config);

		$client = new \Google_Client();
		$client->setClientId($config['client_id']);
		$client->setClientSecret($config['client_secret']);
		$client->setRedirectUri($config['redirect_uri']);
		$client->setDeveloperKey($config['developer_key']);

		$this->client = $client;
		$this->service = new $service_class($client);
	}

	public static function forge($service = null, $name = 'default', array $config = array())
	{
		
		if ($exists = static::instance($name))
		{
			\Error::notice('Gdata with this name exists already, cannot be overwritten.');
			return $exists;
		}

		static::$_instances[$name] = new static($service, $name, $config);

		if ($name == 'default')
		{
			static::$_instance = static::$_instances[$name];
		}

		return static::$_instances[$name];
	}

	public static function instance($instance = null)
	{
		if ($instance !== null)
		{
			if ( ! array_key_exists($instance, static::$_instances))
			{
				return false;
			}

			return static::$_instances[$instance];
		}

		if (static::$_instance === null)
		{
			static::$_instance = static::forge();
		}

		return static::$_instance;
	}

}

/* end of file gdata.php */
