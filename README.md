Overview
========

This Symfony2 bundle aims to provide classes to build your own report

The idea is:

1. Create a configuration file with display options, your queryBuilder or sql statement or an array of data.
2. Display your report in your controller via display helper, exportable in excel / html / csv

Theory
======

The report is built in 3 steps:

- Define your data, means simple or very complicated (advise a treatment of the data with [Flamingo](https://github.com/Earls/FlamingoCommandQueue/blob/master/README.md) in order to simplify Data, faster render) SQL statement, can use doctrine too

- Build your display and calculation on top of it, define your excel options and HTML options

- Render in your controller


Documentation
=============

For installation and how to use the bundle refer to [Resources/doc/index.md](https://github.com/Earls/RhinoReport/blob/master/Resources/doc/index.md)

