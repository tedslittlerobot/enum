TLR \ PHP Enum Library
======================

[![Build Status](https://travis-ci.com/tedslittlerobot/enum.svg?branch=master)](https://travis-ci.com/tedslittlerobot/enum)

A raw PHP Enum / Flags library. Heavily based on [myclabs/php-enum](https://github.com/myclabs/php-enum) (I did not fork, as I made some subtle changes to the core classes).

With support for Laravel (and Nova).

Key features:

- Instantiatable, type-hintable enums.
- Flag Enum type (for help / usage with flags & masks).

Key features for Laravel:

- Enum validation rule.
- Helpers to get displayable values out of enums.
- Getters and setters for eloquent usage.
- Easy Enum field for Laravel Nova.

## Installation

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

### Basic Explanation

Flags (or bit masks) are a common feature of many programming langauges - PHP uses some in its standard library under the guise of some bitmask constants - see [the second argument to json_encode](http://php.net/manual/en/function.json-encode.php) for an example.

They are a kind of enum, where each value is an increasing power of 2 (1, 2, 4, 8, 16, etc.). While a flag is technically just an integer value, if you represent those integers in binary, you get something interesting:

- `1  : 00001`
- `2  : 00010`
- `4  : 00100`
- `8  : 01000`
- `16 : 10000`

If you assign a *meaning* to each bit position (starting from the right), then you have a way of using an integer value to store a set of switched, or flags. You can chain these together - for example, the integer `5` is `00101` - or 1 and 4 combined; the integer 31 is all of them combined; the integer 0 is none of them.

This may seem somewhat complicated at first, but once you have a feel for this, flags can be a very powerful tool, allowing you to pass a full set of option switches in a single argument, without losing any of the contextual meaning of the switches. In the above example, we may assign some *names* to the values (here, using the `json_encode` example - correct values at the time of writing).

- `1  : 00001 : JSON_HEX_TAG`
- `2  : 00010 : JSON_HEX_AMP`
- `4  : 00100 : JSON_HEX_APOS`
- `8  : 01000 : JSON_HEX_QUOT`
- `16 : 10000 : JSON_FORCE_OBJECT`

You can pass the first three to `json_encode` with the `|` operator like so:

```php
$flag = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS;
json_encode([], $flag);
```

You can check if the flag matches a possible flag value with the `&` operator:

```php
$flag & JSON_HEX_AMP; // true
$flag & JSON_FORCE_OBJECT; // false
```

Of course you can check if it matches an exact set with `===`:

```php
$flag === JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS; // true
$flag === JSON_HEX_TAG; // false
```

There is much more you can do with th ebitwise operators and flags, but these are the basics.

### Caveat

Once a flag value has been defined, it can never change in a future version of an application. If PHP decided to remove `JSON_HEX_AMP` and add a new possible switch, `JSON_HEX_SPACE` for example, they would have to add `JSON_HEX_SPACE` to the end of the list of values, and simply not allow the value for `JSON_HEX_AMP`, or ignore it if it is passed (ie. it would *deprecate* the value `2`).

Flags should be used for core things that are not often changed.

### Flags and Enums

The `json_encode` options are just consts set to certain values (powers of 2, as described above). The advantage of using flags with a package like this one, is that the flags, values, and validation, become encapsulated in an object, making definition, referencing, validation, and comparison easier.

The `Flag` class and `Enum` class used above share the same base class, so almost all of the same things from above apply, with a few differences for defining, and a few extra methods for comparing.

### Defining Flags

To use a very simple permissions set as an example.

```php
class Permission extends Flag
{
    protected static $flags = [
        'MANAGE_STAFF',
        'MANAGE_RESEARCH_PROJECTS',
        'VIEW_SECRET_RESEARCH_PROJECTS',
        'VIEW_RESEARCH_REPORTS',
    ];
}

// in some code

$user->permissions = Permission::MANAGE_STAFF();
$reporter->permissions = Permission::VIEW_RESEARCH_REPORTS();
```

### Using masks of flags

The following are all the same:

```php
$user->permissions = Permission::MANAGE_STAFF();
$user->permissions = new Permission(0b0001);
$user->permissions = new Permission(1); // although you would probably get this from some user input.
```

If we could only set the user to be able to do ONE of those things, though, it would be a bit limiting. We can assign multiple flags to one value like so:

```php
// The following are equivalent:
$user->permissions = Permission::combineFlags([
    Permission::VIEW_SECRET_RESEARCH_PROJECTS(),
    Permission::VIEW_RESEARCH_REPORTS(),
]);

$user->permissions = new Permission(
    Permission::VIEW_SECRET_RESEARCH_PROJECTS()->value() | Permission::VIEW_RESEARCH_REPORTS()
);

$user->permissions = new Permission(0b1100);
$user->permissions = new Permission(12);
```

### Comparing Flags

In addition to the enum comparison methods (like `$enum->is($other)`), there are some specific to flags.

// @todo - comparing

// @todo - reading

## Laravel

// @todo

### Validation Rule

// @todo

### Nova Field

// @todo
