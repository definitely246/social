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
		$this->setDecoder();
	}

	/**
	 * This gives us a login url for the serviceName we provide
	 * 
	 * @param  [type] $serviceName [description]
	 * @return [type]              [description]
	 */
	public function login($serviceName)
	{
		$serviceName = strtolower($serviceName);
		$service = $this->getService($serviceName, $this->requestUrl($serviceName));

		switch ($serviceName)
		{
			case 'twitter':
				$token = $service->requestRequestToken();
				$url = $service->getAuthorizationUri(array('oauth_token' => $token->getRequestToken()));
			break;

			default: $url = $service->getAuthorizationUri();
		}

		return $url;
	}

	/**
	 * [check description]
	 * @param  [type] $serviceName [description]
	 * @return [type]              [description]
	 */
	public function check($serviceName)
	{
		try {
			call_user_func_array(array($this, $serviceName), array("user"));
			return true;
		} catch (\Exception $e) {
			return false;
		}
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

		return $this->getDecoder($service->request($request));
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

		return $this->getDecoder($service->request($request));
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

		return $this->getDecoder($service->request($request));
	}
	
	/**
	 * [github description]
	 * @param  string $request [description]
	 * @return [type]          [description]
	 */
	public function github($request = '')
	{
		$service = $this->getService('github', $this->requestUrl('github'));

		if ($request == '') {
			return $service;
		}

		if ($request == 'user') {
			$request = 'https://api.github.com/user';
		}

		return $this->getDecoder($service->request($request));
	}

	/**
	 * [getDecoder description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function getDecoder($data)
	{
		$json_closure = $this->json_closure;
		return $json_closure($data);
	}

	/**
	 * [setDecoder description]
	 * @param [type] $json_closure [description]
	 */
	public function setDecoder($json_closure = null)
	{
		if ($json_closure === null) {
			$json_closure = function($data) {
				return json_decode($data);
			};
		}

		$this->json_closure = $json_closure;
	}

	/**
	 * [service description]
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	protected function getService($name, $url)
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
	protected function requestUrl($serviceName)
	{
		$url = $this->config->get('social::routing.prefix') . "/$serviceName/connect";
		return $this->url->to($url);
	}

}
