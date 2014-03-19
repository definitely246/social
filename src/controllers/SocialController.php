<?php

namespace Codesleeve\Social;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;
use Controller;

class SocialController extends Controller {

	/**
	 * [facebookConnect description]
	 * @return \Illuminate\Support\Facades\Response
	 */
	public function facebookConnect()
	{
		$service = App::make('social')->facebook();

		if (Input::get('code', null)) {
			$service->requestAccessToken( Input::get('code') );
		}

		return Redirect::to(Config::get('social::facebook.redirect_url'));
	}

	/**
	 * [twitterConnect description]
	 * @return \Illuminate\Support\Facades\Response
	 */
	public function twitterConnect()
	{
		$service = App::make('social')->twitter();

		if (Input::get('oauth_token', null)) {
			$service->requestAccessToken( Input::get('oauth_token'), Input::get('oauth_verifier') );
		}

		return Redirect::to(Config::get('social::twitter.redirect_url'));
	}

	/**
	 * [googleConnect description]
	 * @return \Illuminate\Support\Facades\Response
	 */
	public function googleConnect()
	{
		$service = App::make('social')->google();

		if (Input::get('code', null)) {
			$service->requestAccessToken( Input::get('code') );
		}

		return Redirect::to(Config::get('social::google.redirect_url'));
	}
	
	/**
	 * [githubConnect description]
	 * @return \Illuminate\Support\Facades\Response
	 */
	public function githubConnect()
	{
		$service = App::make('social')->github();

		if (Input::get('code', null)) {
			$service->requestAccessToken( Input::get('code') );
		}

		return Redirect::to(Config::get('social::github.redirect_url'));
	}

}
