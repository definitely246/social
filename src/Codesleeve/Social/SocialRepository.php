<?php

namespace Codesleeve\Social;

use OAuth\OAuth2\Service\Facebook;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;
use OAuth\ServiceFactory;

class SocialRepository extends ServiceFactory {

	/**
	 * [__construct description]
	 * @param [type] $config [description]
	 */
	public function __construct($config, $url)
	{
		$this->config = $config;
		$this->url = $url;
	}

	/**
	 * [service description]
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function getService($name, $url)
	{
		$key = $this->config->get("social::$name.key");
		$secret = $this->config->get("social::$name.secret");
		$scopes = $this->config->get("social::$name.scopes");

		$credentials = new Credentials($key, $secret, $url);
		$storage = new Session;

		$factory = new ServiceFactory;
		return $factory->createService($name, $credentials, $storage, $scopes);
	}

	/**
	 * [requestUrl description]
	 * @param  [type] $serviceName [description]
	 * @return [type]              [description]
	 */
	public function requestUrl($serviceName)
	{
		$url = $this->config->get('social::routing.prefix') . "/$serviceName/connect";
		return $this->url->to($url);
	}

	/**
	 * [login description]
	 * @param  [type] $serviceName [description]
	 * @return [type]              [description]
	 */
	public function login($serviceName)
	{
		return $this->getService($serviceName, $this->requestUrl($serviceName))->getAuthorizationUri();
	}

	/**
	 * [facebook description]
	 * @param  string $request [description]
	 * @return [type]          [description]
	 */
	public function facebook($request = '')
	{
		$service = $this->getService('facebook', $this->requestUrl('facebook'));

		if ($request == '') {
			return $service;
		}

		if ($request == 'user') {
			$request = '/me';
		}

		return json_decode($service->request( $request ), true);
	}

	/**
	 * [twitter description]
	 * @param  string $request [description]
	 * @return [type]          [description]
	 */
	public function twitter($request = '')
	{
		$service = $this->getService('twitter', $this->requestUrl('twitter'));

		if ($request == '') {
			return $service;
		}

		if ($request == 'user') {
			$request = 'account/verify_credentials.json';
		}

		return json_decode($service->request($request));
	}

	/**
	 * [google description]
	 * @param  string $request [description]
	 * @return [type]          [description]
	 */
	public function google($request = '')
	{
		$service = $this->getService('google', $this->requestUrl('google'));

		if ($request == '') {
			return $service;
		}

		if ($request == 'user') {
			$request = 'https://www.googleapis.com/oauth2/v1/userinfo';
		}

		return json_decode($service->request($request), true);
	}



}

