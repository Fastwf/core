# Getting started

## Installation

After project initialisation using composer, update the `composer.json` file to add the Fast Web Framework git repository.

```json
{
    "name": "author/app",
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Fastwf/core"
        }
    ],
    "autoload": {
        "psr-4": {
            "Author\\App\\": "src/"
        }
    },
    "require": {}
}
```

Install the `fastwf/core` package:

```bash
$ composer require "fastwf/core" "dev-main"
> ./composer.json has been updated
> Running composer update fastwf/core
> Loading composer repositories with package information
> Updating dependencies                                 
> Lock file operations: 1 install, 0 updates, 0 removals
>   - Locking fastwf/core (dev-main 31b77e6)
> Writing lock file
> Installing dependencies from lock file (including require-dev)
> Package operations: 1 install, 0 updates, 0 removals
>   - Downloading fastwf/core (dev-main 31b77e6)
>   - Installing fastwf/core (dev-main 31b77e6): Extracting archive
> Generating autoload files
```

## Initialisation

The minimum required to create and run an application it is to create 2 files :
- Application engine
- index.php

### Application Engine

The application engine must be extended to define the application settings.

So start by creating the application engine `src/AppEngine.php`.

```php
<?php
// src/AppEngine.php

namespace Author\App;

use Fastwf\Core\Router\Route;
use Fastwf\Core\Engine\Engine;

class AppEngine extends Engine {

    /**
     * Return the list of settings objects. 
     */
    protected function getSettings() {
        return [

        ];
    }

}
```

### Index.php

Fast Web Framework allows to define a unique file that will be exposed by the web server.

Continue and create the script entry point that run the application engine `public/index.php`.

```php
<?php
// public/index.php

require_once __DIR__ . '/../vendor/autoload.php';

use Author\App\AppEngine;

$app = new AppEngine(__DIR__ . '/config.ini');
$app->run();

```

## Settings

To define the required behaviour for your application, one or more setting class must be
implemented using settings interfaces (`Fastwf\Core\Settings` namespace).

> See the [setting interfaces](./settings.md).

One of these settings is the `RouteSettings` and it allows to add request handlers for routes.

Create the `RootSettings` class to add the first request handler in `src/RootSettings.php`.

```php
<?php
// src/RootSettings.php

namespace Author\App;

use Fastwf\Core\Router\Route;
use Fastwf\Core\Settings\RouteSettings;

use Author\App\HelloWorldHandler;

class RootSettings implements RouteSettings
{

    public function getRoutes($engine)
    {
        return [
            new Route([
                'path' => '',
                'methods' => ['GET'],
                'handler' => function ($context) { return new HelloWorldHandler($context); },
                'name' => 'hello-world'
            ])
        ];
    }

}
```

Now the app engine will be able to call the `HelloWorldHandler` when the client call the `GET / HTTP/1.1`

Create the request handler class `src/HelloWorldHandler.php`.

```php
<?php
// src/HelloWorldHandler.php

namespace Author\App;

use Fastwf\Core\Components\RequestHandler;
use Fastwf\Core\Http\Frame\HttpResponse;

class HelloWorldHandler extends RequestHandler {

    public function handle($request) {
        return new HttpResponse(200, [], "hello world\n");
    }

}
```

To finish, the `RootSettings` class can be injected in the `AppEngine`.

```diff
// src/AppEngine.php

namespace Author\App;

use Fastwf\Core\Router\Route;
use Fastwf\Core\Engine\Engine;
++
++ use Author\App\RootSettings;

class AppEngine extends Engine {

    /**
     * Return the list of settings objects. 
     */
    protected function getSettings() {
        return [
++            new RootSettings()
        ];
    }

}
```

## Test the application

Now the application is ready to be used.

```bash
$ cd public
$ php -S localhost:8000 index.php
> PHP 7.4.21 Development Server (http://localhost:8000) started
```

Open another terminal andrun the curl command.

```bash
$ curl http://localhost:8000/
> hello world
```
