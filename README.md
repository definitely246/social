## What the heck is this Social package?

*_A video is coming soon._*

This package's goal is to quickly integrate oauth and social media sites like facebook, twitter and google into your laravel 4 application.

We do this with a preconfigured setup of oauth plugins that piggybacks off of the very popular lusitanian/phpoauthlib. The project uses a facade for oauth and api's to the most popular social websites (facebook, twitter, google, etc) and within minutes after installing this package to have oauth integrated.

## Installation

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `codesleeve/social`.

It might look something like:

```php
  "require": {
    "laravel/framework": "4.0.*",
  	"codesleeve/social": "dev-master"
  }
```

Next, update Composer from the Terminal:

```php
    composer update
```

Once this operation completes add the service provider. Open `app/config/app.php`, and add a new item to the providers array.

```php
    'Codesleeve\Social\SocialServiceProvider'
```

And put in the `Social` facade under the `aliases` array in `app/config/app.php`.

```php

     'Social' => 'Codesleeve\Social\Social',
```

Lastly generate a package config that you can work with.

```php
  php artisan config:publish codesleeve/social
```

## Usage

First you will need to configure the plugin. Let's walk through how to do it for facebook. You can see a complete list of other social sites below.

Next go edit the file at `app/config/packages/codesleeve/social/config.php`, here is a example for facebook,

```php
  'facebook' => array(
  	'key' => '',
  	'secret' => '',
  	'scopes' => array('email'),
  	'redirect_url' => '/',
  ),
```

You will need to add at least a `key` and `secret` which can be obtained by [creating a new facebok app][dev_facebook]

After the user logs in, if you want to redirect them somewhere besides the base path of your laravel application, then you can change the `redirect_url`.

Once you have configured facebook application open up a view and place

    <a href="<?= Social::login('facebook') ?>">Login to facebook</a>

Now that you've connected with facebook, now you can access Facebook data about that user (provided you have requested that [permission in your scopes array inside the package config](https://developers.facebook.com/docs/reference/login/#permissions)) 

You can pass api requests as a parameter to the `Social::facebook` facade, like so:

```php
  $user = Social::facebook('/me');
```

You can see if a user is logged into a service like so

```php
  if (Social::check('facebook')) {
    ...
  }
```

Another thing we've added is a common interface to get the logged in user by passing 'user' as the request.

```php
  $user = Social::facebook('user');
  $user = Social::twitter('user');
  $user = Social::google('user');
```

This keeps us from having to deal directly with the api when we just simply want a user's info and nothing more.

### Decoding json from apis

If you decide you want to decode your data differently from a provider you can set the decoder function. The example below shows how to decode into an associative array. 

```php
        Social::setDecoder(function($data) {
            return json_decode($data, true);
        });
```

*_Note though_* that this changes the decoder for all of the apis using Social, if you need to reset the decoder back you can do,

```php
        Social::setDecoder(null);
```

## Full example page

```html
  <!doctype html>
  <html lang="en" class="login page">
  <head>
    <meta charset="8-UTF">
    <title>Login</title>
  </head>
  <body>
    <div class="container">
      <div class="login-social">
        <a href="<?= Social::login('facebook') ?>"><img src="http://ottopilotmedia.com/wp-content/uploads/2012/07/facebook-icon.jpg"></a>
      </div>
    </div>

    <pre>
      <?php if (Social::check('facebook')): ?>
        <?= print_r( Social::facebook('/120500222/feed') )?>
      <?php endif; ?>
    </pre>

  </body>
  </html>
```

## Support

Here is a list of social sites we support currently. More will be added later... I think... maybe... yeah, well... I donno... yeah, I think so.

  - Facebook
  - Twitter
  - Google
  - GitHub

Please file an issue if you see a problem. And enjoy!


[dev_facebook]: https://developers.facebook.com/apps  "Create an app on facebook developers site"
