# Wordpress Attachment Exporter

Version 0.1.0

**Warning**: This is a PHP library, not a Wordpress _plugin_. Therefore, it can only be used inside a PHP file.

Ever had to migrate a huge Wordpress website from one server to another ? If you did, you probably know it can be a pain in the ass since Wordpress creates thousands of thumbnails and stores them all amongst the original files. If you don't want to waste hours downloading and re-uploading the `/uploads/`, I could help you.

My thought was to only select the registered original attachment files, and put them in a ZIP file I could easily download. Well, that's exactly what this library does.

But why only the registered original files ? Because you actually don't need all the thumbnails and other duplicates of the original files. The URL to the original file is the only important data one stored in the database. This means you can only migrate those files and regenerate the thumbnails afterwards, using an existing plugin or whatever you want.

Let's get started.

## Install

Download the core and `require` the autoload file:

```
require_once('WpAttachmentExporter/autoload.php');
```

Make sure this is done somewhere inside Wordpress's scope, because we'll need the `$wpdb` object (and some other other Wordpress stuff).

## Create an export

Nothing easier! Create an instance of the `Export` class:

```
$export = new WpAttachmentExporter\Export();
```

And generate the ZIP archive:

```
$export->zip();
```

That's all. The ZIP file is stored in `WpAttachmentExporter/tmp/WpAttachmentExport.zip`.

## Additional methods

Even if the first `zip()` method already takes care of a lot of work, there are some other useful fucntions you may need.

### Delete the generated ZIP

In continuity of the above example:

```
$export->clean();
```

## Debugging & loging

In order to truly know what's happening during the exports, the library logs every useful info in a `log` array. This way, you can easily output the report of the export.

E.g.

```
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