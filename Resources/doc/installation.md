Add the bunde to your `composer.json` file:
```javascript
require: {
    // ...
    "earls/rhino-report-bundle": "dev-master@dev",
    "lexik/form-filter-bundle": "v2.0.3"
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