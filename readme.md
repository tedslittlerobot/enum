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

### Setup

You can declare the values of the enum in three ways:

As above - using const declarations when declaring a class. This should probably be considered the "default" way of declaring an enum.

```php
class ArticleType extends Enum 
{
    const BLOG_POST   = 'blog';
    const REVIEW      = 'review';
    const RECIPE      = 'recipe';
    const CODE_SAMPLE = 'code';
}
```

In an `$enum` static variable.


```php
class ArticleType extends Enum 
{
    protected static $enum = [
        'BLOG_POST'   = 'blog',
        'REVIEW'      = 'review',
        'RECIPE'      = 'recipe',
        'CODE_SAMPLE' = 'code',
    ];
}
```

Override the `generateEnums` method (the default implementation defines the above two ways of declaring the values). This could be used to load the values from a database, etc. and should be used with care. This method will only be called once, as the result of it is cached.

```php
class ArticleType extends Enum
{
    /**
     * Get the values for the enum
     *
     * @return array
     */
    public static function generateEnums() : array
    {
        return [
            'BLOG_POST'   = 'blog',
            'REVIEW'      = 'review',
            'RECIPE'      = 'recipe',
            'CODE_SAMPLE' = 'code',
        ];
    }
}
```

### Instantiating an Enum

The two main ways of instantiating an enum are:

You can use any of the declared enum **names** to statically instantiate an enum instance. This is useful when manually declaring a value - perhaps to save to the database.

```php
ArticleType::REVIEW();
```

You can provide the **value** of the enum to the constructor. This is useful when loading values from a database, or getting input from a user.

```php
new ArticleType($request->input('article_type'));
```

You can also get a list of all instantiated enums with the all static method:

```php
foreach(ArticleType::all() as $name => $enum) {
    echo "{$name} : {$enum->value()}";
}
```

#### Getting information on an enum

You can get various bits of information out of an enum using helper methods:

```php
$type = ArticleType::CODE_SAMPLE();

$type->value();        // 'code'
$type->name();         // 'CODE_SAMPLE'
$type->friendlyName(); // 'Code Sample'
```

The friendlyName method can be used to display values to a user. It will attempt to make the key name into words, and `Title Case` them. You can override this in two ways:

Override the `friendlifier` static method. It will be passed the key name of the enum, and should return its friendly value.

```php
protected static function friendlifier(string $name) : string;
```

Override the `friendlyNames` static method, which should return an array/map with the friendly names on the left, and the ordinary values on the right.

```php
public static function friendlyNames() : array
{
    return [
        return [
            'Blog Post'   = 'blog',
            'Review'      = 'review',
            'Recipe'      = 'recipe',
            'Code Sample' = 'code',
        ];
    ];
}
```

#### Comparing Enums

You can compare any enum against another with the `is` or `equals` methods (they are the same, both are included in case they make more syntactical / English sense when writing code)

```php
$type = $request->input('type'); // 'review'
(new ArticleType($type))->is(ArticleType::REVIEW()); // true
```

This comparison takes into account the class name of the enum, as well as its value, so multiple different enums will not be able to be cross-compared to the original enum.

```php
ArticleType::RECIPE()->is(SharedItemType::RECIPE()); // false
```

## Flag Enums

// @todo - intro - how to use flags

// @todo - similarities to enums

// @todo - instantiating & intermediary values

// @todo - comparing

// @todo - reading

## Laravel

// @todo

### Validation Rule

// @todo

### Nova Field

// @todo
