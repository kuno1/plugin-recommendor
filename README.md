# Plugin Recommendor

WordPress theme add-on to recommend plugins.

## How to Use

In your theme, install via composer.

```
composer require kunoichi/plugin-recommender
```

Then, include `autoload.php` in your `functions.php`.

```php
require_once __DIR__ . '/vendor/autoload.php';
```

Now you can refer to class `Kunoichi\PluginRecommender`.

```php
Kunoichi\PluginRecommender::bulk_add( [
  [
    slug        => 'jetpack',
    description => __( 'Because automattic recommends this plugin.', 'domain' ),
  ],
  [
    slug     => 'contact-form-7',
    priority => 100,
  ],
] );
```

## Properties

Here's a list of properties which you can set for each plugin.

`slug`

**Required**. This should be same as WordPress.org.

`source`

Optional. Default is `wp` and if you provide some extra repository, set it's name. Of course, you need extra filters for it to work properly.

`priority`

Optional. Recommended plugins ordered in descent. Over 90 means "required", over 50 measn "integrated" and others are simply recommended.

`description`

Optional. If this recommendation requires some text to recognize it's efficiency, just write.

## API

`Kunoichi\PluginRecommender::bulk_add( $settings )`

Bulk load setttings. Pass array of settings.

`Kunoichi\PluginRecommender::add( $setting )`

Add single plugin to recommend list.

`Kunoichi\PluginRecommender::load( $json )`

JSON file which represents `$settings` is also available.
But JSON is not translation-ready.

`Kunoichi\PluginRecommender::load()`

Just put `recommendations.json` in your theme directory.

## License

GPL 3.0 or later.
