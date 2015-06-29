This file will give you the information to build your definition.

Contents
=====

- [head()](#head)
- [body()](#body)
- [group()](#group)
- [row()](#row)
- [rowUnique()](#rowunique)
- [column()](#column)
- [columnData()](#columndata)
- [action()](#action)
- [attr()](#attr)
- [extendingGroupAction()](#extendinggroupaction)
- [formulaExcel()](#formulaexcel)
- [configExport()](#configexport)
- [baseData()](#basedata)
- [groupBy()](#groupby)
- [rowSpan()](#rowspan)
- [columnSpan()](#columnspan)
- [end()](#end)
- [headColumns()](#headcolumns)


Tree Hierarchy
==============

```php
public function getConfigReportDefinition(Request $request, $dataFilter)
{
  $reportDefinitionBuilder = new ReportDefinitionBuilder();
  $reportDefinitionBuilder
    ->table()
      ->attr()
      ->configExport()
      ->head()
        ->headColumns()
      ->end()//end head
      ->body()
        ->attr()
        ->rowSpan()
        ->groupBy()
        ->row()
          ->attr()
          ->columnSpan()
          ->column()
            ->attr()
            ->baseData()
            ->extendingGroupAction()
            ->action()
            ->groupAction()
            ->formulaExcel()
          ->end()//end colmun
          ->columnData()
            //same as Column tag
          ->end()/end columnData
        ->end()//end row
        ->rowUnique()
          //same as Row tag
        ->end()//end rowUnique
        ->group()
          ->attr()
          ->groupBy()
          ->row()
            //same as Row tag above
          ->end()//end row
          ->group()
            //same as Group tag
          ->end()//end group
        ->end()//end group
      ->end()//end body
    ->end();//end table
    
  //build report definition
  return $reportDefinitionBuilder->build();
}
  
```

head()
=====

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td><a href="report_definition.md#table">table()</a></td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td><a href="#headcolumns">headColumns()</a></td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

This tag allow you to add a selection of column you want to display

###Example
```php
  ->table()
    ->head()
      ->headColumns()
    ->end()
  ->end()
```


body()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td><a href="report_definition.md#table">table()</a></td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>
      <a href="report_definition.md#table">row()</a>
      <br><a href="#rowunique">rowUnique()</a>
      <br><a href="#group">group()</a>
      <br><a href="#rowspan">rowSpan()</a>
      <br><a href="#groupby">groupBy()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

It will be the core of your table, where all data are displayed. The body is simply a group with the id "body".

###Example
```php
  ->table()
    ->body()
      //...
    ->end()
  ->end()
```

group()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="#body">body()</a>
      <br><a href="#group">group()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>
      <a href="report_definition.md#table">row()</a>
      <br><a href="#rowunique">rowUnique()</a>
      <br><a href="#group">group()</a>
      <br><a href="#rowspan">rowSpan()</a>
      <br><a href="#groupby">groupBy()</a>
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

This tag allow to organize per group your reports, it is not displayed a screen, it just a tool to group the data per 
group. The data injected in the group will come from the previous group divided by groupBy 
if it is existing in your current group.

###Example
```php
  ->body()
    ->group($id)
      //...
    ->end()
  ->end()
```

row()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="#body">body()</a>
      <br><a href="#group">group()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>
      <a href="#column">column()</a>
      <br><a href="#columndata">columnData()</a>
      <br><a href="#columnspan">columnSpan()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

This tag is the description of your row and column contained in it. This definition will 
be displayed on the screen has many times your set of data from its group contains rows. This tag is 
the same as [RowUnique](#rowunique), the difference is RowUnique will be displayed one time, no matter how 
many rows is contained by the set of data.

###Example
```php
  ->group()
    ->row()
      //...
    ->end()
  ->end
```

rowUnique()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="#body">body()</a></td>
      <br><a href="#group">group()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>
      <a href="#column">column()</a>
      <br><a href="#columndata">columnData()</a>
      <br><a href="#columnspan">columnSpan()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

This tag is the same as [row()](#row), the difference is rowUnique will be displayed once on the table.
It will get the first row of the set of Data coming from its group or bodyand display the information 
on the screen.

###Example
```php
  ->group()
    ->rowUnique()
      //...
    ->end()
  ->end
```

column()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="#row">row()</a>
      <br><a href="#rowunique">rowUnique()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>
      <a href="#attr">attr()</a>
      <br><a href="#basedata">baseData()</a>
      <br><a href="#extendinggroupaction">extendingGroupAction()</a>
      <br><a href="#action">action()</a>
      <br><a href="#groupaction">groupAction()</a>
      <br><a href="#formulaexcel">formulaExcel()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>
      string $displayId   this id do the link between the tag and the headColuns
    </td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>
      string $dataId    this id do the link between the tag and the Dataset
    </td>
  </tr>
</table>

###Description

This tag tells to the report which information you want to display in the cell of the table,
it can be a data from the dataset, just a label, a combination of other columns or a calculation 
depending on the elements you defined.

###Example
```php
  ->row()
    ->column($displayId) //need the end tag when only one argument given
      //...
    ->end()
  ->end()

  //alternative
  ->row()
    ->column(displayId, $dataId) //do not need the end tag when 2 arguments given
  ->end()
```

columnData()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="#row">row()</a>
      <br><a href="#rowunique">rowUnique()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>
      <a href="#attr">attr()</a>
      <br><a href="#basedata">baseData()</a>
      <br><a href="#extendinggroupaction">extendingGroupAction()</a>
      <br><a href="#action">action()</a>
      <br><a href="#groupaction">groupAction()</a>
      <br><a href="#formulaexcel">formulaExcel()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>
      string $displayId   this id do the link between the tag and the headColuns
    </td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>
      string $dataId    this id do the link between the tag and the Dataset
    </td>
  </tr>
</table>

###Description

Same as a column, this tag define the content of the cell, but this tag wont be displayed on the screen, 
it is just a column to help you create calculations and combined data.When you need 
to prepared data for an other column.

###Example
```php
  
  ->row()
    ->columnData($displayId) //need the end tag when only one argument given
      //...
    ->end()
  ->end()

  //alternative, not very useful in this case but still possible
  ->row()
    ->columnData(displayId, $dataId) //do not need the end tag when 2 arguments given
  ->end()
```

attr()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="report_definition.md#table">table()</a>
      <br><a href="#body">body()</a>
      <br><a href="#group">group()</a>
      <br><a href="#row">row()</a>
      <br><a href="#rowunique">rowUnique()</a>
      <br><a href="#column">column()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>
      array   $attributes   an array of attributes, mainly html attributes 
    </td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

This tag give to add custom tag on the report. On html render those attributes will be attribute of 
<td> table tag. The "style" tag can be add too and it wil change the design of your column. In Excel, style 
will be converted and will be displayed (limited by Excel Xml capacity). 

List of possible tags :
- custom : what ever you want
- style : style for html, or for excel depending on the TemplateGenerator
- class : for html it will add a class on <td>, those class will be also by Excel

###Example
```php
  ->column($displayId)
    ->attr(
      array(
        'style' => array('text-indent' => '15px'),
        'class' => array('number'),
        'custom' => 'plopi'
      )
    )
```

action()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="#body">body()</a>
      <br><a href="#group">group()</a>
      <br><a href="#row">row()</a>
      <br><a href="#rowunique">rowUnique()</a>
      <br><a href="#column">column()</a>
      <br><a href="#columndata">columnData()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>
      string $name    The name of the action you want to use
      <br>array $arg    The arguments for this action
    </td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

This tag will give you possibility to change the content of the column parent. For Example 
apply on the data a format, add a label etc... You can add your own [action](dev_addactions.md)

The scope of the action, which means where the action can get data, is only on columns of the same group, 
except this the action wont be able to get data from a group, you will have to use a groupAction 
for this matter.


###Example
```php
  ->column()
    ->action($name, $args)
  ->end()
```

groupAction()
============

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="#body">body()</a>
      <br><a href="#group">group()</a>
      <br><a href="#row">row()</a>
      <br><a href="#rowunique">rowUnique()</a>
      <br><a href="#column">column()</a>
      <br><a href="#columndata">columnData()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>
      string $name    The name of the action you want to use
      <br>array $arg    The arguments for this action
    </td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>
      array   $dependencies   an array of other groupAction on which this element depends
    </td>
  </tr>
</table>

###Description

This tag give you the possibility to work with other columns, for example in order to 
do a sum of several rows. But it can be as well used on group or rows in specific cases.
Like the action your can easily create your own. See [action](#action). 

The scope of the groupAction, means where the groupAction can get data, is on the 
"table" the groupAction is working on, means quite large.

###Example
```php
  ->column()
    ->groupAction($dependencies)
  ->end()
```

extendingGroupAction()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="#body">body()</a>
      <br><a href="#group">group()</a>
      <br><a href="#row">row()</a>
      <br><a href="#rowunique">rowUnique()</a>
      <br><a href="#column">column()</a>
      <br><a href="#columndata">columnData()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>
      array   $dependencies   an array of other groupAction on which this element depends
    </td>
  </tr>
</table>

###Description

This tag allow to extend a groupAction with the column. Because the action will 
always be executed before a groupAction. In the case you want to use a value from a column 
in your action but this previous column contains a groupAction, you will get data before groupAction 
is executed, which can be nothing. So you need to wait and define an dependency on the previous 
column and it is where you have to use the tag extendingGroupAction().

###Example
```php
  ->column()
    ->extendingGroupAction()
  ->end()
```

formulaExcel()
=============

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="#column">column()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>
      string    $formula    a formula has simple as it is, each columns will be represented by %s (see sprintf funciton form php)
      <br>array    $columns   an array of column ids
    </td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

This tag allow to add formula to excel export

###Example
```php
  ->column()
    ->formulaExcel($formula, $columns)
  ->end()
```

configExport()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>all</td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>
      array   $allExportConfig    an array of all config for the element, in the array as well an array for each export available
    </td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

This tag allow to add new config specific for the export it specified.
It is an extension of attribute but more specific. 

For example on Html, you can define Attribute, which will be applied only on html render.
It makes more sense when used with Excel, in this case, you can defined Pagebreak / Print options / RptInfo etc... 

###Example
```php
  //options on table
  ->table()
    ->configExport(
      array(
        'html' => array(
          'attr' => see attr()
        ),
        'excel' => array (
          'rpt_info' => $tableDefinition,  //It is a table in a table, this table will be displayed on the head of the excel document, inception !!
          'column' => array(    //define the width of each column based on displayId
              'posdescr' => array('width' => 19.43),
              'stkdescr' => array('width' => 24.57),
          ),
          'style_table' => array(   //this will be the equivalent of a css file
              'format-dollar' => array(
                'number-format-code' => '$#,##0.00;($#,##0.00)'
              ),
              'format-percent' => array(
                'number-format-code' => '#,##0.00%;(#,##0.00%)'
              ),
              'format-int' => array(
                'number-format-code' => '#,##0;(#,##0)'
              )
          ),
          'print' => array( //can be an object
            'orientation' => 'landscape',
            'margin' => 'narrow',
            'scaling' => 'column-in-one-page',
            'print_titles' => true //print headColumns
          )
        )
      )
    )
  ->end()
  //option on group
  ->group()
    ->configExport(
      array(
        'html' => array(
          'attr' => see attr()
        ),
        'excel' => array (
          'pagebreak' => true
        )
      )
    )
  ->end()
  //option on row
  ->row()
    ->configExport(
      array(
        'html' => array(
          'attr' => see attr()
        ),
        'excel' => array (
          'attr' => array(
            'classForColumns' => array('level1'), //will apply for its columns the same class
          )
        )
      )
    )
  ->end()
  //option on column
  ->column()
    ->configExport(
      array(
        'html' => array(
          'attr' => see attr()
        ),
        'excel' => array (
          'attr' => array(
            'type' => 'number', //will cast the type before sending data to excel into float
            'class' => 'format-int'   //class used by style_table, see above in table
          )
        )
      )
    )
  ->end()
```

baseData()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="#column">column()</a>
      <br><a href="#columndata">columnData()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>
      string    $dataType   which type of ID do you want to call
      <br>string    $id   the id either from the dataset if type is 'dataId', either from the report if type is 'DisplayId'
    </td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

Define the base of the data you want to specify for the column, can be another column or from the dataset

List of $dataType: (should be constant...)
- 'dataId'
- 'displayId'

###Example
```php
  ->column()
    ->baseData('dataId', $id)
  ->end()
```

groupBy()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="#group">group()</a>
      <br><a href="#body">body()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>
      string    $id   id of the column you want to groupBy, the $id will be a dataId
    </td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

This tag allow to divide the dataset by the column specified. So each group will be generated, 
as much as group of data it is existing in the dataset, so the data on screen will be modified 
in the order of the group.

###Example
```php
  ->group()
    ->groupBy($id)
  ->end()
```

rowSpan()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="#group">group()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>
      array   $displayIds   id of the columns
    </td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

It will apply a rowspan on display for each column given.

###Example
```php
  ->group()
    ->rowSpan($displayIds)
  ->end()
```

columnSpan()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="#row">row()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>
      array   $displayIds   id of the columns
    </td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

It will apply a rowspan on display for each column given.

###Example
```php
  ->row()
    ->colSpan($displayIds)
  ->end()
```

end()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td>
      <a href="report_definition.md#table">table()</a>
      <br><a href="#head">head()</a>
      <br><a href="#body">body()</a>
      <br><a href="#group">group()</a>
      <br><a href="#row">row()</a>
      <br><a href="#rowunique">rowUnique()</a>
      <br><a href="#column">column()</a>
      <br><a href="#columndata">columnData()</a>
    </td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

Give the signal of the end of the element

###Example
```php
  ->column()
  ->end()
```

headColumns()
======

<table>
  <tr>
    <td><b>Parent element:</b></td>
    <td><a href="#head">head()</a></td>
  </tr>
  <tr>
    <td><b>Required elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Optional elements:</b></td>
    <td>(none)</td>
  </tr>
  <tr>
    <td><b>Required attributes:</b></td>
    <td>
      array   $columnNames    name of columns
    </td>
  </tr>
  <tr>
    <td><b>Optional attributes:</b></td>
    <td>(none)</td>
  </tr>
</table>

###Description

For each column you can define a title name and a displayId. There is as well the 
possibility to add more options if you replace, the value of the array by an array.

###Example
```php
  ->head()
    ->headColumns(
      array(
        // 'displayId' => 'title'
        'posdescr' => 'POS Item',
        'stkdescr' => 'Corp Item',
      )
  )
  ->end()

  //more options
  ->head()
    ->headColumns(
      array(
        'timekey' => 'Time segment', //no options
        'resto_mo' => array(
          'label' => '<span>Restaurant</span>', 
          'attr' => array('class' => 'vertical-text') //react as attr()
        ),
      )
  )
  ->end()
```


