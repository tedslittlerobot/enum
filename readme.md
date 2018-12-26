Tlr\Phpnum
========

A Laravel (and Nova) enum package. Heavily based on [myclabs/php-enum](https://github.com/myclabs/php-enum) (I did not fork, as I made some subtle changes to the core classes).

Key features:

- Instantiatable, type-hintable enums.
- Flag Enum type (for help / usage with flags & masks).

Key features for Laravel:

- Enum validation rule.
- Helpers to get displayable values out of enums.
- Getters and setters for eloquent usage.
- Easy Enum field for Laravel Nova.

## Installation

**Not on packagist yet**, as these docs are not finished, but when it is, it will be available to install using:

```bash
composer require tlr/enum
```

If you are on Laravel with package auto-detection, good for you, you're all set up. If not, add `Tlr\Phpnum\Laravel\EnumServiceProvider` to your loaded service providers.

## What is an enum?

An enumaration value - it is a value that represents one possible in a concrete list of values. It allows you to define a list of specific values, and be confident that your enum conforms to that list.

Consider the following function:

```php
function createNewArticle(string $type) {
    // ...
}
```

Any string can be passed in to this function - 'article', 'recipe', 'monkeys', and while it may be easy to handle many possible types, you will always have to handle any types that don't exist.

An enum would allow you to type hint a value from a pre-determined list of values that are definied in your code, so you can be sure that you are dealing with a discrete (limited) choice of values (types, in this case). For example...

## Basic Usage (Enums)

> Personally, I keep an application namespace specifically for all of enums in a project, and for the sake of these examples, I will be using the `App\Values` as the namespace for enums.

```php
<?php

namespace App\Values;

use Tlr\Phpnum\Enum;

class ArticleType extends Enum 
{
    const BLOG_POST   = 'blog';
    const REVIEW      = 'review';
    const RECIPE      = 'recipe';
    const CODE_SAMPLE = 'code';
}
```

```php
use App\Values\ArticleType;

$type = ArticleType::REVIEW();

$type->value(); // 'review'
$type->is($type); // true
$type->is(ArticleType::RECIPE()); // false

$typeFromDb = new ArticleType('review');
$type->is($typeFromDb); // true

new ArticleType('bleh'); // throws exception - not in enum

// ...

function createNewArticle(ArticleType $type) {
    if($type->is(ArticleType::RECIPE())) {
        // do something here...
    }

    switch($type->value()) {
        case ArticleType::RECIPE():
        case ArticleType::BLOG_POST():
            break;
    }
}
```

## Full Docs (Enum)

// @todo

## Full Docs (Flag)

// @todo
