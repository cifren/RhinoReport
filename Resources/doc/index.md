Installation
============

Add the bunde to your composer.json file:
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

Then run a composer update:
    composer.phar update
    # OR
    composer.phar update earls/earls/rhino-report-bundle # to only update the bundle

Register the bundle with your kernel:
    // in AppKernel::registerBundles()
    $bundles = array(
        // ...
        new Earls\RhinoReportBundle\EarlsRhinoReportBundle(),
        // ...
    );

Usage
=====

Here a simple example of report creation



