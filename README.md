# Wordpress Attachment Exporter

_Version 0.1.1_

**Warning**: This is a PHP library, not a Wordpress _plugin_. Therefore, it can only be used inside a PHP file.

Ever had to migrate a huge Wordpress website from one server to another ? If you did, you probably know it can be a pain in the ass since Wordpress creates thousands of thumbnails and stores them amongst the original files. If you don't want to waste hours downloading and re-uploading the `/uploads/` directory, I can help you.

My thought was to select the registered original attachment files and put them in a ZIP file, which I could easily download. Well, that's exactly what this library does.

But why only the registered original files ? Because you actually don't need all the thumbnails or other duplicates of the original files. The URL to the original file is the only important data stored in the database. This means you only should migrate those files and regenerate the thumbnails afterwards, using an existing plugin or whatever you want.

Let's get started.


## Install

Download the core and `require` the autoload file:

```php
require_once('WpAttachmentExporter/autoload.php');
```

Make sure this is done somewhere inside Wordpress's scope, because we'll need the `$wpdb` object (and some other Wordpress stuff).


## Create an export

Nothing easier! Just make an instance of the `Export` class:

```php
$export = new WpAttachmentExporter\Export();
```

And generate the ZIP archive:

```php
$export->zip();
```

That's all. The ZIP file is now stored in `/WpAttachmentExporter/tmp/WpAttachmentExport.zip`.


## Additional methods

Even if the first `zip()` method already takes care of a lot of work, there are some other useful functions you may need.


### Download ZIP

You can download the generated zip directly from your browser:

```php
$export->download();
```

This will automatically perform a `clean()` action once the file is downloaded. If you want to prevent this behavior, just pass `false` to the `download()` method:

```php
// The generated ZIP will remain on the server
$export->download(false);
```

### Delete ZIP manually

Easy!

```php
$export->clean();
```

### Loging

If you want to know what happened during the export, you should take a look at the logs. There are different ways to read the logs, but using the `log()` method is probalby the quickest. This method will create a file containing all the information you should need. More info about loging in the section below (Debugging & loging).

```php
// This will generate a file named wpattachmentexporter-log-[date]
// The file will be place at the scope's root (probably wordpress's root).
$export->log();

// You can specify a path where to put the logs if you want
// (the directory has to exist and will not be created automatically)
$export->log('../logs/WAE');
```


## Debugging & loging

In order to truly know what's happening during the exports, the library logs every useful info in a `log` array. This way, you can easily output the report of the export.

E.g.

```php
require_once('WpAttachmentExporter/autoload.php');

$export = new WpAttachmentExporter\Export();
$export->zip();

foreach($export->log as $item){
      echo ucfirst($item->type) . ': ' . $item->message . '<br />';
}
```

There are 3 `type`s of logs:

- `error`: The library encoutered a problem that will prevent the ZIP file from being generated correctly.
- `warning`: There was a little problem (probably with one of the attachments), but it will not affect the ZIP archive.
- `success`: Ow look! A bird! A double rainbow!


## Contributing

If you want to help making this library even better, feel free to make a pull-request, open an issue or to contact me with your further ideas!