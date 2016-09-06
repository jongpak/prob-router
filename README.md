# Prob/Router
*A simple http request router library*

[![Build Status](https://travis-ci.org/jongpak/prob-router.svg?branch=master)](https://travis-ci.org/jongpak/prob-router)
[![codecov](https://codecov.io/gh/jongpak/prob-router/branch/master/graph/badge.svg)](https://codecov.io/gh/jongpak/prob-router)

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

// use zend-diactoros package (for PSR-7)
use Zend\Diactoros\Request;
use Zend\Diactoros\Uri;

$dispatcher = new Dispatcher(require 'url.mapping.php');
```

```php
// print 'Hello main'
$dispatcher->dispatch(
    (new Request())
        ->withUri(new Uri('http://test.com/'))
        ->withMethod('GET')
);
```

```php
// print 'Test page!'
$dispatcher->dispatch(
    (new Request())
        ->withUri(new Uri('http://test.com/test'))
        ->withMethod('GET')
);
```

```php
// print 'Post ID: 5'
$dispatcher->dispatch(
    (new Request())
        ->withUri(new Uri('http://test.com/post/5'))
        ->withMethod('GET')
);
```
