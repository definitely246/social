<?php

Route::group(Config::get('social::routing'), function() {

	Route::get('/facebook/connect', '\Codesleeve\Social\SocialController@facebookConnect');
	Route::get('/twitter/connect', '\Codesleeve\Social\SocialController@twitterConnect');
	Route::get('/google/connect', '\Codesleeve\Social\SocialController@googleConnect');
	Route::get('/github/connect', '\Codesleeve\Social\SocialController@githubConnect');

});
