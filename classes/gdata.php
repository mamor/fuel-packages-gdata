<?php

namespace Gdata;

if (file_exists(__DIR__.'/../vendor/autoload.php'))
{
	require_once __DIR__.'/../vendor/autoload.php';
}
else
{
	require_once VENDORPATH.'autoload.php';
}

/**
 * FuelPHP Gdata package
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */
class Gdata
{
	/**
	 * @var Gdata
	 */
	protected static $_instance;

	/**
	 * @var array
	 */
	protected static $_instances = array();

	/*
	 * Initialize
	 */
	public static function _init()
	{
		\Config::load('gdata', true);
	}

	/**
	 * Forge
	 * 
	 * @param  string $service
	 * @param  string $name
	 * @param  array $config
	 * @return Gdata
	 */
	public static function forge($service = null, $name = 'default', array $config = array())
	{
		
		if ($exists = static::instance($name))
		{
			\Error::notice('Gdata with this name exists already, cannot be overwritten.');
			return $exists;
		}

		static::$_instances[$name] = new static($service, $config);

		if ($name == 'default')
		{
			static::$_instance = static::$_instances[$name];
		}

		return static::$_instances[$name];
	}

	/**
	 * Get instance
	 * 
	 * @param  string $instance
	 * @return mixed
	 */
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

	/**
	 * Google \Google_Client
	 */
	public $client;

	/**
	 * Google Service Class
	 */
	public $service;

	/**
	 * Constructor
	 * 
	 * @param  string $service
	 * @param  array $config
	 * @throws \FuelException
	 */
	public function __construct($service, array $config)
	{
		$service_class = '\Google_Service_'.$service;
		if( ! class_exists($service_class))
		{
			throw new \FuelException('Could not find Gdata service class: '.$service_class);
		}

		$config = array_merge(\Config::get('gdata'), $config);

		$client = new \Google_Client();
		$client->setApplicationName($config['application_name']);
		$client->setClientId($config['client_id']);
		$client->setClientSecret($config['client_secret']);
		$client->setRedirectUri($config['redirect_uri']);
		$client->setDeveloperKey($config['api_key']);
		$client->setScopes($config['scopes']);
		$client->setAccessType($config['access_type']);

		$this->client = $client;
		$this->service = new $service_class($client);
	}

}

/* end of file gdata.php */
