Report definition builder
=========================

The report definition builder is the class that will help you to build your display. 
This class will create the report definition in background.

Contents
=====

[table()](#table)


##Tree Hierarchy

Table()
=====

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>
      <a href="https://github.com/earls/RhinoReport/blob/master/Resources/doc/report_table_definition.md#body">body()</a>
    </td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>
      <a href="https://github.com/earls/RhinoReport/blob/master/Resources/doc/report_table_definition.md#attr">attr()</a>
      <br><a href="https://github.com/earls/RhinoReport/blob/master/Resources/doc/report_table_definition.md#configExport">configExport()</a>
      <br><a href="https://github.com/earls/RhinoReport/blob/master/Resources/doc/report_table_definition.md#head">head()</a>
      <br><a href="https://github.com/earls/RhinoReport/blob/master/Resources/doc/report_table_definition.md#end">end()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>string $id</td>
  </tr>
</table>

###Description

description here

###Example
```php
  ->table($id)
    ->attr(/*...*/)
    ->configExport(/*...*/)
    ->head()
      //...
    ->end()
    ->body()
    ->end()
  ->end()
```

