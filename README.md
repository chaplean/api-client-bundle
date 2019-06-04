# Chaplean Api Client Bundle

[![build status](https://travis-ci.org/chaplean/api-client-bundle.svg?branch=master)](https://travis-ci.org/chaplean/api-client-bundle)
[![Coverage status](https://coveralls.io/repos/github/chaplean/api-client-bundle/badge.svg?branch=master)](https://coveralls.io/github/chaplean/api-client-bundle?branch=master)
[![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/chaplean/api-client-bundle/issues)

Library to help defining client for rest apis.

## Table of content

* [Installation](#Installation)
* [Creating bundles based on api-client-bundle](#creating-bundles-based-on-api-client-bundle)
    * [Configuration](#Configuration)
    * [Creating an Api class](#creating-an-api-class)
    * [Defining an Api](#defining-an-api)
* [Using a bundle based on api-client-bundle](#using-a-bundle-based-on-api-client-bundle)
* [Additional Features](#additional-features)
* [Commands](#commands)
* [Versioning](#Versioning)
* [Contributing](#Contributing)
* [Hacking](#Hacking)
* [License](#License)

## Installation

This bundle requires at least Symfony 3.0.

You can use [composer](https://getcomposer.org) to install api-client-bundle:
```bash
composer require chaplean/api-client-bundle
```

Then add to your AppKernel.php:
```php
new EightPoints\Bundle\GuzzleBundle\EightPointsGuzzleBundle(),
new Chaplean\Bundle\ApiClientBundle\ChapleanApiClientBundle(),

// If you want to enable Database logging
new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),

// If you want to enable Email logging
new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
new Symfony\Bundle\TwigBundle\TwigBundle()
```

## Creating bundles based on api-client-bundle

This section describes how you can create your own api bundle based on this project. If you want examples see our own api bundles on [packagist](https://packagist.org/?query=chaplean%2F%20client-bundle) or [github](https://github.com/chaplean?utf8=%E2%9C%93&q=client-bundle&type=&language=).

### Configuration

First you will need to configure guzzlehttp that we use under the hood to perform the actual http
requests. See the [bundle](https://github.com/8p/EightPointsGuzzleBundle) documentation or the [library](http://docs.guzzlephp.org/en/latest/request-options.html) documentation for the full range
of options.

config.yml:
```yaml
eight_points_guzzle:
    logging: true
    clients:
        fake_api:
            # We inject guzzle configuration from parameters.yml but we could hardcode it here
            options: %fake_api.options%
```

You will also probably want to create some custom parameters.

parameters.yml:
```yaml
parameters:
    # Guzzle configuration
    fake_api.options:
        timeout: 10
        verify: false
        expect: false

    # Your custom configuration, here we just define the base url of our fake_api
    fake_api.url: 'http://fakeapi.com/'
```

As you inject guzzle in your Api class you can have different configuration per Api. See next [section](#creating-api).

### Creating an Api class

To use api-client-bundle you have to create a class extending AbstractApi. You can create any number of classes extending AbstractApi and have all of them using different
configurations via dependency injection.

```php
<?php

use Chaplean\Bundle\ApiClientBundle\Api\AbstractApi;
use Chaplean\Bundle\ApiClientBundle\Api\Parameter;
use GuzzleHttp\ClientInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FakeApi extends AbstractApi
{
    protected $url;

    /**
     * AbstractApi requires you to pass it a GuzzleHttp\ClientInterface and an EventDispatcherInterface,
	 * we also inject the base api of our fake_api
     */
    public function __construct(ClientInterface $client, EventDispatcherInterface $eventDispatcher, $url)
    {
        $this->url = $url;

        // buildApi() is called automatically by the parent constructor, make sure you call it at the END of the construct() function.
        parent::__construct($client, $eventDispatcher);
    }

    /**
     * We define our api here, we'll dig into this in the next section
     */
    public function buildApi()
    {
        $this->globalParameters()
            ->urlPrefix($this->url) // here we set base url
            ->expectsJson();

        $this->get('fake_get', 'fake')
            ->urlParameters([
                'id' => Parameter::id(),
            ]);
    }
}
```

```yaml
services:
    App\Bundle\ApiBundle\Api\FakeApi:
        arguments:
            $client: '@guzzle.client.fake_api' # Guzzle client we defined in config.yml
            $url:    '%fake_api.url%'          # the base url of fake_api
```

And we're done! We could repeat this process to create another Api with completely different configurations.

#### Defining an Api

Let's focus on the ```buildApi()``` function you have to fill in and what we can do in it.
The role of this function is to define your Api using the api-client-bundle's api:

```php
<?php

public function buildApi()
{
    /*
     * You have to call this function first to set basic config
     */
    $this->globalParameters()
        ->urlPrefix('http://some.url/')  // set the base url of our api
        ->urlSuffix('/some-suffix')      // Configure a suffix on our url (Optional, default: empty)

    /*
     * We can then set some configurations that will be the default for every route we create later.
     * You have the exact same api available here and available when configuring routes.
     * See route definition for detailed descriptions of headers(), urlParameters(), queryParameters() and requestParameters()
     */
        ->expectsPlain()                 // Declare we expect responses to be plain text
        ->expectsJson()                  // Declare we expect responses to be json
        ->expectsXml()                   // Declare we expect responses to be xml

        ->sendFormUrlEncoded()           // Configure that we post our data as classic form url encoded
        ->sendJson()                     // Configure that we post our data as json
        ->sendXml()                      // Configure that we post our data as xml
        ->sendJSONString()               // Configure that we post our data as a url-encoded key-value pair where the key is JSONString and the value is the request data in json format

        ->headers([])                    // Configure what headers we send
        ->urlParameters([])              // Configure what url placeholders we define
        ->queryParameters([])            // Configure what query strings we send
        ->requestParameters([]);         // Configure what post data we send

    /*
     * Here we define the core of our api, the routes. We can use get(), post(), put(), patch(), delete() functions
     * with a route name and a route url (with placeholders in you want) to define routes.
     */
    $this->get('query_one', 'data/{id}');
    $this->post('create_one', 'data');
    $this->patch('update_one', 'data/{id}');
    $this->put('update_one', 'data/{id}');
    $this->delete('delete_one', 'data/{id}');

    /*
     * Those function return the route object to further configure it.
     * As said previously the route api is the same as the one we get with globalParameters().
     */
    $this->post('create_one', 'data/{id}')
        ->expectsPlain()                 // Declare we expect responses to be plain text
        ->expectsJson()                  // Declare we expect responses to be json
        ->expectsXml()                   // Declare we expect responses to be xml

        ->sendFormUrlEncoded()           // Configure that we post our data as classic form url encoded
        ->sendJson()                     // Configure that we post our data as json
        ->sendXml()                      // Configure that we post our data as xml
        ->sendJSONString()               // Configure that we post our data as a url-encoded key-value pair where the key is JSONString and the value is the request data in json format

        ->headers([])                    // Configure what headers we send
        ->urlParameters([])              // Configure what url placeholders we define
        ->queryParameters([])            // Configure what query strings we send
        ->allowExtraQueryParameters()    // Allow extra field in query parameters
        ->requestParameters([])          // Configure what post data we send
        ->allowExtraQueryParameters();   // Allow extra field in request parameters

    /*
     * Finally calling headers(), urlParameters(), queryParameters() or requestParameters() without configuring parameters is sort of useless.
     * So let's see how to define parameters.
     */
    $this->put('update_data', 'data/{id}')
        ->urlParameters(                 // Define the placeholder parameter for the {id} in the url
            [
                'id' => Parameter::id(),
            ]
        )
    /*
     * We define a list of key => values pairs where key is the name of the parameter and the value is a parameter type.
     */
        ->requestParameters(
            [
                'name'     => Parameter::string(),
                'birthday' => Parameter::dateTime('Y-m-d'),
                'is_human' => Parameter::bool()->defaultValue(true),
                'height'   => Parameter::int(),
                'weight'   => Parameter::float()->optional(),
                'tags'     => Parameter::object(
                    [
                        'id'   => Parameter::id(),
                        'name' => Parameter::string(),
                    ]
                ),
                'friends'  => Parameter::arrayList(Parameter::id()),
                'enum'     => Parameter::enum(['foo', 'bar']),
            ]
        );
    /*
     * Last but not least, you can also directly give any instance of Parameter. Here we use ArrayParameter.
     */
        ->requestParameters(Parameter::arrayList(
            Parameter::object(
                [
                    'id'     => Parameter::id()
                ]
            )
        ))

    /*
     * Passing an array is actually a shortcut that implies ObjectParameter since it's the most common.
     * 
     * The following two definitions are equivalent.
     */
        ->requestParameters(
            [
                'id'     => Parameter::id()
            ]
        )

        ->requestParameters(Parameter::object(
            [
                'id'     => Parameter::id()
            ]
        ));
}
```

## Parameter options

List of options for the parameters

```php 
    // Options available for all types of Parameter
    Parameter::xyz()
        ->optional()             // Define the parameter optional
        ->defaultValue('value')  // Define a default value for the field
        
    // Options specific to object Parameter
    Parameter::object()
        ->allowExtraField()      // Allow sending a field not defined in the configuration
```

## Using a bundle based on api-client-bundle

This section describes how to use a bundle based api-client-bundle.

As shown in the previous [section](#defining-an-api) the api defines a list of routes and the parameters they accept. To call a route you need to provide them.

For an api with the following definition:

```php
class FakeApi
{
    ...

    public function buildApi()
    {
        $this->get('user', '/user/{id}')
            ->urlParameters(['id' => Parameter::id()])
            ->expectsJson()
    }

    ...
}
```

We can call the `getUser()` method, provide the parameters and run the request:
```php
$response = $api->getUser()           // the get('user', '/user/{id}') definition added a getUser() method
    ->bindUrlParameters(['id' => 42]) // we provide a value for the 'id' parameter
    ->exec();                         // we can now execute the request
```

Here we called `bindUrlParameters()` to provide values for the parameters defined with `urlParameters()`. Similarly, for parameters defined with `headers()`, `queryParameters()` and `requestParameters()` there is a `bindHeaders()`, `bindQueryParameters()` and `bindRequestParameters()`.

You have to call these functions with a `key => value` array. There is a validation pass during `exec()` before running the request making sure the values you provided match the definitions in the api.

Finally, `exec()` returns a `ResponseInterface`. Several implementations of this interface exist:
* InvalidParameterResponse: The parameters provided didn't weren't valid;
* RequestFailedResponse: Request performed but failed (network issue or non 2xx status code);
* PlainResponse: Request suceeded and route was either defined with `expectsPlain()` or it wasn't specified;
* JsonResponse: Request suceeded and route was defined with `expectsJson()`;
* XmlResponse: Request suceeded and route was defined with `expectsXml()`;

Among the functions in `ResponseInterface` here are some usefull ones and how you could use them:
```php
if ($response->succeeded()) {                 // Was the response a 2xx?
    // The request suceeded.
    $content = $response->getContent();       // Get the body of the response,
                                              // will be a string for plain text
                                              // and associative array for json and xml.
    ...
} else {
    $violations = $response->getViolations(); // If the provided parameters were invalid.
                                              // this will contain the violations.
    if (!empty($violations)) {
        // The request failed because of invalid parameters.
        ...
    } else {
        // The request failed due to a network issue or the response was not a 2xx.
        ...
    }
}

```

## Additional Features

This bunde expose some configuration if you want to enable extra features. You can enable database and / or email logging of requests.
To use the database or email loggers you will have to setup respectively [doctrine](https://symfony.com/doc/current/doctrine.html) or [swiftmailer](https://symfony.com/doc/current/email.html) in your project.
The default configuraton is:

config.yml:
```yaml
chaplean_api_client:
    # Specify when to log api requests. You can give a boolean to enable or disable globally
    # or give a white list of clients where logging is only enabled for the listed clients (ex: ['foo_api', 'bar_api'])
    # or a black list where logging is enabled for all clients excepted those listed (ex: ['!foo_api', '!bar_api']).
    enable_database_logging: false
    enable_email_logging: false
    email_logging:
        # Limit emails to the specified codes.
        # You can either use a code directly like 200, 404, ...
        # or use XX to say all codes in the familly like 5XX to say all server errors.
        # 0 means that the request failed to run (either because of invalid parameters or a networking error)
        codes_listened: ['0', '1XX', '2XX', '3XX', '4XX', '5XX']
        address_from: ~
        address_to:   ~
```

You can override the default email content by overriding the translation keys or even the email body twig template.
The translation keys are under `chaplean_api_client.email.request_executed_notification` and the template is `Resources/views/Email/request_executed_notification.txt.twig`.

## Commands

To clean the logs from the Database, you may use the command `chaplean:api-logs:clean [minimumDate]`. It will remove old logs, only keeping those more recent than the given `minimumDate` date. By default, this argument date is `now -1 month`. It should be formatted as a [PHP's DateTime string](https://www.php.net/manual/fr/datetime.formats.php).

This command makes irreversible changes in your database, so we strongly recommend you to back up the logs before executing it.

## Versioning

api-client-bundle follows [semantic versioning](https://semver.org/). In short the scheme is MAJOR.MINOR.PATCH where
1. MAJOR is bumped when there is a breaking change,
2. MINOR is bumped when a new feature is added in a backward-compatible way,
3. PATCH is bumped when a bug is fixed in a backward-compatible way.

Versions bellow 1.0.0 are considered experimental and breaking changes may occur at any time.

## Contributing

Contributions are welcomed! There are many ways to contribute, and we appreciate all of them. Here are some of the major ones:

* [Bug Reports](https://github.com/chaplean/api-client-bundle/issues): While we strive for quality software, bugs can happen and we can't fix issues we're not aware of. So please report even if you're not sure about it or just want to ask a question. If anything the issue might indicate that the documentation can still be improved!
* [Feature Request](https://github.com/chaplean/api-client-bundle/issues): You have a use case not covered by the current api? Want to suggest a change or add something? We'd be glad to read about it and start a discussion to try to find the best possible solution.
* [Pull Request](https://github.com/chaplean/api-client-bundle/pulls): Want to contribute code or documentation? We'd love that! If you need help to get started, GitHub as [documentation](https://help.github.com/articles/about-pull-requests/) on pull requests. We use the ["fork and pull model"](https://help.github.com/articles/about-collaborative-development-models/) were contributors push changes to their personnal fork and then create pull requests to the main repository. Please make your pull requests against the `master` branch.

As a reminder, all contributors are expected to follow our [Code of Conduct](CODE_OF_CONDUCT.md).

## Hacking

You might find the following commands usefull when hacking on this project:

```bash
# Install dependencies
composer install

# Run tests
bin/phpunit
```

## License

api-client-bundle is distributed under the terms of the MIT license.

See [LICENSE](LICENSE.md) for details.
