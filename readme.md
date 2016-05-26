**This package provides a conventional front-end for [riari/laravel-forum](https://github.com/Riari/laravel-forum) 3.0 and above. It should be installed along with the forum unless a custom or alternative front-end implementation is required.**

**Complete documentation is available on [teamteatime.net](http://teamteatime.net/docs/laravel-forum/3.0/front-end/introduction.md).**

## Installation

### Step 1: Install the package

Install the package via composer:

```
composer require riari/laravel-forum-frontend:~1.0
```

Then add the service provider to your `config/app.php`:

```php
Riari\Forum\Frontend\ForumFrontendServiceProvider::class,
```

### Step 2: Publish the package files

Run the vendor:publish command to publish the package config and views to your app's directories:

`php artisan vendor:publish`

### Additional steps

Once the package is installed, provided you are logged in, you can visit <your domain>/forum and start defining your category hierarchy using the "Create category" and "Category actions" panels:

![Category management example](http://i.imgur.com/h8DXHj1.png)

#### Configuration

The `forum.frontend` config file defines the controllers used by the package as well as a closure used to process alert messages to be displayed to the user.

#### Views

Views are published to `resources/views/vendor/forum`. The simplest way to integrate the forum with your existing design is to edit the **master** view, remove undesired markup and make it extend your application's main layout with `@extends`. Note that the master view does pull in jQuery and Bootstrap 3 by default, and includes some jQuery-based JavaScript to support some of the forum frontend features. You may wish to move it elsewhere or re-write it in your own way.

#### Events

The package includes a variety of [events](http://laravel.com/docs/5.1/events) for user interactions such as viewing threads. Refer to [src/Events](https://github.com/Riari/laravel-forum-frontend/tree/master/src/Events) for a full list.
