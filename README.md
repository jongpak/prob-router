# Prob/Router
*A simple http request router library*

## Usage

### 1. Configure url mapping and handler
url.mapping.php
```php
<?php

use Prob\Router\Map;

$map = new Map();
$map->setNamespace('app\\controller');

$map->get('/', function ($url) {
    echo 'Hello main!';
});

$map->get('/test', 'Test.hello');
$map->get('/post/{post:int}', 'Post.view');

return $map;
```

### 2. Write your request handler (controller)
app/controller/Test.php
```php
<?php

namespace app\controller;

class Test
{
    public function hello()
    {
        echo 'Test page!';
    }
}
```

app/controller/Post.php
```php
<?php

namespace app\controller;

class Post
{
    public function hello($req)
    {
        echo 'Post ID: ' . $req['post'];
    }
}
```

### 3. Apply!
index.php
```php
<?php

use Prob\Router\Dispatcher;
use Prob\Rewrite\Request;

$dispatcher = new Dispatcher(require 'url.mapping.php');
$dispatcher->dispatch(new Request());
```