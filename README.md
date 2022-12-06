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

Here's a list of properties that you can set for each plugin.

### slug

**Required**. This should be the same as WordPress.org.

### source

Optional. The default is `wp`. If you provide some extra repository, set its name. Of course, you need an extra filter `plugin_recommender_information` for it to work properly.

### priority

Optional. Recommended plugins ordered in descent. Over 90 means "required", over 50 means "integrated", and others are simply recommended.

### description

Optional. If this recommendation requires some explanation to recognize its efficiency, just write.

## API

`Kunoichi\PluginRecommender::bulk_add( $settings )`

Bulk load settings. Pass array of settings.

`Kunoichi\PluginRecommender::add( $setting )`

Add a single plugin to recommend list.

`Kunoichi\PluginRecommender::load( $json_file_path )`

JSON file, which represents `$settings` is also available.
But JSON is not translation-ready.

`Kunoichi\PluginRecommender::load()`

Just put `recommendations.json` in your theme directory.

`Kunoichi\PluginRecommender::set_title( $string )`  
`Kunoichi\PluginRecommender::set_menu_title( $string )`  
`Kunoichi\PluginRecommender::set_description( $string )`

Change the page title, menu title, and description.

## License

GPL 3.0 or later.
