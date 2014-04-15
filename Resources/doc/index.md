Installation
============

Add the bunde to your `composer.json` file:
```javascript
require: {
    // ...
    "earls/rhino-report-bundle": "dev-master@dev"
    // ...
},
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/Earls/RhinoReport.git"
    }
]
```

Then run a `composer update`:
```shell
composer.phar update
# OR
composer.phar update earls/rhino-report-bundle # to only update the bundle
```

Register the bundle with your `kernel`:
```php
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Earls\RhinoReportBundle\EarlsRhinoReportBundle(),
    // ...
);
```

Usage Documentation
===================

* [First Report] (https://github.com/earls/RhinoReport/blob/master/Resources/doc/firstReport.md)
* [Advanced report]()
* [Filter on your report]()
* [Actions list]()
* [Add more GroupActions]()


Developer Documentation
=======================

* [Design Concept]()
* [Add your own Actions] ()

Improvements
============

- Fix bug on group when no groupBy