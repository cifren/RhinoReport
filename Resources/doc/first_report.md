Here a simple example of `report` creation. 

In this Example, we will display the 5 last result of our company.

You need a table structure :
  
  yearResult table linked with entity

    idData|YearData|resultData  
    ------|--------|------------
    1     |2012    |135599.59875
    2     |2013    |152569.32569
    3     |2014    |120000.32588


The folder structure of our example :

```
-- src/
---- Project/
------ ReportBundle/
-------- Config/
---------- FirstReportConfiguration.php
-------- Entity/
---------- YearResult.php
-------- Controller/
---------- ReportController.php
-------- Resources
---------- views
------------ Report
-------------- FirstReport.html.twig
```

- Entity (YearResult.php) is the source of your data you inject in your report

- Config (FirstReportConfiguration.php) will setup all information concerning the display and data

- Controller (ReportController.php) in order to manage the display and execution of your report

- View (FirstReport.html.twig) will display our report

Create your `configuration file` :

```php
namespace Project\ReportBundle\Configuration;

use Earls\RhinoReportBundle\Module\Table\Definition\TableDefinitionBuilder;
use Earls\RhinoReportBundle\Report\Definition\ReportConfiguration;
use Doctrine\ORM\EntityManager;

/**
 *  Setup your report display configuration in this file
 *
 *  Project\ReportBundle\Configuration\FirstReportConfiguration
 */
class FirstReportConfiguration extends ReportConfiguration
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        //used to create a queryBuilder
        $this->em = $em;
    }
    
    public function getConfigReportDefinition(Request $request, $dataFilter)
    {
        //this class `ReportDefinitionBuilder` is here to help you to create your definition
        //Via a tree of functions, you will create your report
        $definition = new ReportDefinitionBuilder();
        
        //This definition will build a table of data with display options
        $definition->table('firstTable')
            //Define columns ids and display names
            ->head()
                ->headColumns(array(
                    //the key of the array is called the displayId
                    'yearCol' => 'Year',
                    'resultCol' => 'Company Results',
                    )
                )
            ->end()//head
            
            //start the definition of the body report
            ->body
                
                //This group will display each line of the data
                ->group('result')
                    ->groupBy('id') //dataId, facultative

                    ->row()
                        ->column(
                            'yearCol', //displayId
                            'yearData' //dataId
                        )
                        ->column('resultCol')
                            ->baseData('dataId','resultData')
                            //apply a round on data
                            ->action('round', array('precision' => 2))
                            //apply a money_format on data
                            ->action('money_format', array())
                    ->end() //row end

                ->end() //group end

                //it will display only one line in the table
                ->rowUnique()
                    ->column('yearCol')
                        //apply on the column a simple label
                        ->action('label_field', array('label' => 'Total'))
                    ->end() //column end
                    ->column('resultCol')
                        /** make the sum of all row from group 'result' column 'resultCol', the third
                         *  parameter in the dependency of the group action but we dont use it this example
                         */
                        ->groupAction('sum', array('column' => '\firstTable\body\result.resultCol'), array())
                        //apply a round on data
                        ->action('round', array('precision' => 2))
                        //apply a money_format on data
                        ->action('money_format', array())
                    ->end() //column end
                ->end() //row end
                
            ->end() //body end
        ->end() //table end
        
        return definition;
    }

    public function getQueryBuilder(){
        $qb = $this->em->createQueryBuilder()
            ->select('yr')
            ->from('YearResult', 'yr')
            ->setMaxResults(5)
            ->orderBy('yr.yearData');

        return $qb;
    }
}

```

There are two different type of id you can call in the definition, `dataId` and `displayId`

#### DataId
Represent the data created by the getQueryBuilder, all column from this query will be linked with this id

#### DisplayId
Represent the column in your table, when you want to call a column inside an action
```
//...
  ->headColumns(
    array(
        'yearCol' => 'Year', 
        'resultCol' => 'Company Results'
    ))
//...
  ->column('yearCol', 'yearData')
//...
```
`yearCol` and `resultCol` are both DisplayId, in headColumns it is declared and in 'column' it is here to do the link between `yearCol` and the dataId `yearData`.

###TIPS
    An other possibility to inject data into the report is to use `getArrayData()` function in your `FirstReportConfiguration` class, this function will be executed just after `getQueryBuilder()` in order to apply some modification to the array after QueryBuilder has been used. 
    
    So at this moment you can execute into `getArrayData()` a Raw Sql and return an Array ;)

Next to this you have implement this config into the `ReportBuilder`. This class is the core of RhinoReport, it is where everything is managed, from the filter to the content render.

The `ReportBuilder` will do the merge between your data from `getQueryBuilder()` and your definition from `getConfigReportDefinition`, it will create all rows and store them into the object `Report` you can get from the `ReportBuilder`.

Create your `controller` :

```php
namespace Project\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Project\ReportBundle\Configuration\FirstReportConfiguration;

/**
 *  Project\ReportBundle\Controller\FirstReportController
 */
class ReportController extends Controller
{
   
    public function FirstReportAction()
    {   
        //prepare your entity manager
        $em = $this->getDoctrine()->getManager();
        
        //Instantiate your config
        $firstRptConfig = new FirstReportConfiguration($em);
        
        //Create your report builder, should be a service...
        $rptBuilder = new ReportBuilder(
            $firstRptConfig, //your report configuration
            //some dependency the report builder needs
            $this->get('service_container'), 
            $this->get('lexik_form_filter.query_builder_updater'), 
            $this->get('request'), 
            $this->get('form.factory')
        );

        //build your report, will generate all calculations and the object "Report" at this moment, which is the fusion between data and definition
        $rptBuilder->build();

        //the export manager help you to generate quickly your report and filter, it will give you back html string or Excel file depending or the choice of the user
        $exportManager = $this->get('report.template.generator.manager');

        //will give you all html you need to put directly in your template, you have to give the id of the item you want to get in order to display it on screen, for example id "firstTable"
        $htmlTable = $exportManager->getResponse('html', null, $rptBuilder->getReport(), 'firstTable');

        //your template
        return $this->render('ProjectReportBundle:Report:firstReport.html.twig', array(
                    'htmlTable' => $htmlTable
        ));
    }
}
```

Last things, create your `view` :
```twig
{% extends "::base_sparkbox.html.twig" %}

{% block body %}
    <h1>Sales Report</h1>
    <br>

    <div class="table-responsive">
        {{ htmlTable|raw }}
    </div>
{% endblock %}
```

The page will display a table like :

Year |Company Results
-----|---------------
2014 |$120000.32
2013 |$152569.32
2012 |$135599.59
Total|$408169.23