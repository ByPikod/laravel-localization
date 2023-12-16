# Laravel Localization

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![GitHub Release](https://img.shields.io/github/release/ByPikod/laravel-localization.svg?style=flat)](https://github.com/ByPikod/laravel-localization/releases)
[![Packagist Downloads](https://img.shields.io/packagist/dt/ByPikod/laravel-localization.svg?style=flat)](https://packagist.org/packages/ByPikod/laravel-localization)
[![Discord](https://img.shields.io/discord/748913297042046997?label=discord&logo=discord&style=social)](https://www.yahyabatulu.com/discord)

[![GitHub Stars](https://img.shields.io/github/stars/ByPikod/laravel-localization.svg?style=flat)](https://github.com/ByPikod/laravel-localization/stargazers)
[![GitHub Issues](https://img.shields.io/github/issues/ByPikod/laravel-localization.svg?style=flat)](https://github.com/ByPikod/laravel-localization/issues)
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/ByPikod/laravel-localization.svg?style=flat)](https://github.com/ByPikod/laravel-localization/pulls)

Laravel localization is a solution for making your website entirely **dynamic** and **multilingual** with the easiest way possible.

> [!IMPORTANT]
> This package is mainly created to update & fetch the **website content** from the database. But it also provides a multi-language support for your website. That way you can easily provide dashboards to your clients that they can update the website content multilingually.

> [!NOTE]
> Whether you wanna create a multilingual website or not, you can still use this package to update the website content from the database with the easiest way possible.

> [!NOTE]
> **ByPikod\LaravelLocalization** uses an efficient and easy way to fetch the translations from the database. You can check the [How it works](#how-it-works) section for further information about the innovative methods of **ByPikod\LaravelLocalization**.

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

It is that simple to use **ByPikod\LaravelLocalization**.

## Why Laravel Localization?

The common problem with the localization packages that made me create this package is that they don't have an efficient way to fetch the translations from the database. These are the ways that I have seen so far:

One way is to fetch the translations from the database whenever a function is called. But that is not efficient at all. Because it would get a lot of unnecessary queries to the database. And it would be a huge waste of resource for pages that have a lot of translations.

Another way is we could fetch all the translations from the database at once. But that would be a huge waste of memory since we would have to fetch all the translations at each request even if 90% of them are not used in the document at all.

Another way is to have namespaces that seperates the translations into different groups for each page. But since some pages might have translations in common with other pages, we would still have a little overhead. Despite it is not a big overhead, this solution would also need us to write extra functions that have to determine which namespaces to be fetched from the database for each page. And that would be a waste of time for small projects. Even if this wasn't a bad solution, I was still too lazy to write extra code. I wanted a library that would be efficient and easy to use at the same time.

So I came up with a solution for lazy developers like me. This package only fetches the translations that are used in the document that requested. And it is done efficiently with a single query to the database. So you don't have to worry about the performance of your website.

See [How it works](#how-it-works) section for further information about the innovative method of **ByPikod\LaravelLocalization**.

## How it works

**ByPikod\LaravelLocalization** uses a unique method to fetch the translations from the database as I mentioned before.

First things first, Blade directive `@t` doesnt fetch the translations from the database directly.

When a blade template is rendered, the `@t` directive doesnt fetch the translation from the database directly.

Instead it adds the key of the translation to a collection. Then, it prints a php code that calls the global method `getCachedTranslation` with the key of the translation and the language code as parameters.

Once the blade template is completly rendered, the collection of the translation keys will be retrieved from the database with a single query to the database. Then, the `getCachedTranslation` method will be able to retrieve the translations from the cache.

After that, the PHP rendering process will start and getCachedTranslation functions in the blade templates will be executed. This time, the translations will be retrieved from the cache.

So any translation that is not used in the blade templates will not be retrieved from the database. And there wont be unnecessary queries to the database since the translations will be retrieved at once.

## Contribution

Laravel Localization is a new package and it is not perfect yet. So any contributions are welcome. You can contribute to the project by creating a pull request.

Any ideas or suggestions are also welcome. You can give feedback by creating an issue.

> [!WARNING]
> Please make sure you have created appropriate tests for your code before creating a pull request.

## License

**ByPikod/LaravelLocalization** is open-sourced laravel package licensed under the [MIT license](https://opensource.org/licenses/MIT).
