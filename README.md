# Laravel Localization

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![GitHub Release](https://img.shields.io/github/release/ByPikod/laravel-localization.svg?style=flat)](https://github.com/ByPikod/laravel-localization/releases)
[![Packagist Downloads](https://img.shields.io/packagist/dt/ByPikod/laravel-localization.svg?style=flat)](https://packagist.org/packages/ByPikod/laravel-localization)
[![Discord](https://img.shields.io/discord/748913297042046997?label=discord&logo=discord&style=social)](https://www.yahyabatulu.com/discord)

[![GitHub Stars](https://img.shields.io/github/stars/ByPikod/laravel-localization.svg?style=flat)](https://github.com/ByPikod/laravel-localization/stargazers)
[![GitHub Issues](https://img.shields.io/github/issues/ByPikod/laravel-localization.svg?style=flat)](https://github.com/ByPikod/laravel-localization/issues)
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/ByPikod/laravel-localization.svg?style=flat)](https://github.com/ByPikod/laravel-localization/pulls)

Laravel localization is a solution for making your website entirely **dynamic** and **multilingual** with the easiest way possible.

It detects the translations that are used in the document and fetches them from the database with a single query to the database. This is a efficient way to fetch the translations from the database. It is also very easy to use. You can retrieve the translations with a single blade directive. And you can update the translations with a single helper function.

> [!IMPORTANT]
> This package is mainly created to update & fetch the **website content** from the database. This is useful for websites that have a lot of dynamic content that must be updated from the database. [Official Laravel Localization](https://laravel.com/docs/10.x/localization) is still the best solution for static translations that are not updated from the database. You can use both packages at the same time.

## Table of contents

* [Installation](#installation)
* [Get started](#get-started)
* [Why Laravel Localization?](#why-laravel-localization)
* [How it works](#how-it-works)
* [Contribution](#contribution)

## Laravel Compatibility

| Laravel | Package
|---------|----------
| 10.x    | 1.x
| 9.x     | 1.x
| 8.x     | 1.x
| 7.x     | 1.x

## Installation

You can install the package via composer:

```bash
composer require bypikod/laravel-localization
```

## Get started

**ByPikod\LaravelLocalization** uses blade directives to retrieve the translations from the database. It is done by using the **"@t"** directive. This directive accepts two parameters, the first one is the key of the translation and the second one is the language code. The language code is optional, if you don't provide it, `app()->getLocale()` will be used.

Here is an example of how to use the "@t" directive:

```html
<h1 class="default-language">
    @t('home.title')
</h1>
<h1 class="en">
    @t('home.title', 'en')
</h1>
```

If requested translation is not found for the locale, fallback locale will be used. If fallback locale is not found, the key will be returned.

You can also update the translations from the database by using the helper function `updateTranslation`. This function accepts three parameters, the first one is the key of the translation, the second one is the translation itself and the third one is the language code. The language code is optional, if you don't provide it, `app()->getLocale()` will be used.

Here is an example of how to use the `updateTranslation` helper function:

```php
updateTranslation('home.title', 'Home', 'en');
```

And if you want to update the translations in bulk, you can use the `updateTranslations` helper function as in the example below:

```php
updateTranslations([
    'home.title' => 'Home',
    'home.description' => 'This is the description of the home page.',
], 'en');
```

Also it is possible to update the translations in bulk with multiple languages as in the example below:

```php
updateTranslations([
    'home.title' => [
        'en' => 'Home',
        'tr' => 'Anasayfa',
    ],
    'home.description' => [
        'en' => 'This is the description of the home page.',
        'tr' => 'Bu anasayfanın açıklamasıdır.',
    ],
]);
```

It is that simple to use **ByPikod\LaravelLocalization**.

## Why Laravel Localization?

The common problem with the localization packages that made me create this package is that they don't have an efficient way to fetch the translations from the database. Most of them already doesn't fetch the translations from the database. They just use the translations that are already cached in the memory. But that is not a good solution for websites that have a lot of dynamic content that must be updated from the database. These are the ways that I have seen so far:

* **Fetch Dynamic Content Directly:** One way is to fetch the translations from the database whenever it requested. But that is not efficient at all. Because it would get a lot of unnecessary queries to the database. And it would be a huge waste of resource for pages that have a lot of translations.
* **Fetch All Translations At Once:** Another way is we could fetch all the translations from the database at once. But that would be a huge waste of memory since we would have to fetch all the translations at each request even if 90% of them are not used in the document at all.
* **Fetch By Groups:** Another way is to have namespaces that seperates the translations into different groups for each page. But since some pages might have translations in common with other pages, we would still have a little overhead. Despite it is not a big overhead, this solution would also need us to write extra functions that have to determine which namespaces to be fetched from the database for each page. And that would be a waste of time for small projects. Even if this wasn't a bad solution, I was still too lazy to write extra code. I wanted a library that would be efficient and easy to use at the same time.

So I came up with a solution for lazy developers like me. This package only fetches the translations that are used in the document that requested. And it is done efficiently with a single query to the database. So you don't have to worry about the performance of your website.

See [How it works](#how-it-works) section for further information about the innovative method of **ByPikod\LaravelLocalization**.

## How it works

**ByPikod\LaravelLocalization** uses a unique method to fetch the translations from the database as I mentioned before. Here is how it works step by step:

* First things first, Blade directive `@t` doesnt fetch the translations from the database directly. Instead it adds the key of the translation to a collection and prints a php code that calls the global method `getCachedTranslation` with the key of the translation and the language code as parameters.
* Once the package **"View"** renders the page and creates a PHP file for generated output, `getCachedTranslations` functions will called one by one. First function to be called will retrieve the translations from the database looking at the collection of translation keys that we created previous step with a single query. Following calls to the `getCachedTranslation` method will always return the translations from the cache.

So any translation that is not used in the blade templates will not be retrieved from the database. And there wont be unnecessary queries to the database since the translations will be retrieved at once.

![How it works](/promotions/laravel-localization.svg)

## Contribution

Laravel Localization is a new package and it is not perfect yet. So any contributions are welcome. You can contribute to the project by creating a pull request.

Any ideas or suggestions are also welcome. You can give feedback by creating an issue.

> [!WARNING]
> Please make sure you have created appropriate tests for your code before creating a pull request.

### Areas that need contribution

* More tests are always welcome. More tests means more stability.
* This package needs SQL queries to be efficient as much as possible. Optimizing the SQL queries would be a great contribution.
* Code optimization is also welcome. If you have any idea to make the code more efficient, you can create a pull request or an issue.
* This package is still new and it needs more features. If you have any idea for a new feature, you can create a pull request or an issue.

## License

**ByPikod/LaravelLocalization** is open-sourced laravel package licensed under the [MIT license](https://opensource.org/licenses/MIT).
