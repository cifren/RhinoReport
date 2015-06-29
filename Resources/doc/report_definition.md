The report definition builder is the class that will help you to build your display. 
This class will create the report definition in background.

Contents
=====

[table()](#table)


Tree Hierarchy
==============

```php
public function getConfigReportDefinition(Request $request, $dataFilter)
{
  $reportDefinitionBuilder = new ReportDefinitionBuilder();
  $reportDefinitionBuilder
    ->table() //here start a new builder, table definition builder
    ->end();
    
  //build report definition
  return $reportDefinitionBuilder->build();
}
  
```

table()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>
      <a href="report_table_definition.md#body">body()</a>
    </td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>
      <a href="report_table_definition.md#attr">attr()</a>
      <br><a href="report_table_definition.md#configExport">configExport()</a>
      <br><a href="report_table_definition.md#head">head()</a>
      <br><a href="report_table_definition.md#end">end()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>
      string $id    this id is used when you will need to retrieve the object during rendering, by default it will be 'table'
    </td>
  </tr>
</table>

###Description

This tag will create a new definition builder giving you more tags, the doc for [TableDefinitionBuilder](report_table_definition.md), 
this has been like this in order to give the possibility to create new definition builder 
autonomous, for example charts.

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

