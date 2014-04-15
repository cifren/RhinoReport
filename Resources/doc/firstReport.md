Your first report
=================

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

Controller (ReportController.php) in order to manage the display and execution of your report
View (FirstReport.html.twig) will display our report
Config (FirstReportConfiguration.php) will setup all information concerning the display and data


Create your `configuration file` :
```php
namespace Project\ReportBundle\Configuration;

use Earls\RhinoReportBundle\Definition\Table\TableDefinitionBuilder;
use Earls\RhinoReportBundle\Definition\ReportConfiguration;
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
        $definition->table('firstTable')
            //Define columns ids and display names
            ->head()
                ->headColumns(array(
                    //those are the displayId
                    'yearCol' => 'Year',
                    'resultCol' => 'Company Results',
                    )
                )
            ->end()//head
            ->body

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
        $em = $this->getDoctrine()->getEntityManager();
        
        //Instantiate your config
        $firstRptConfig = new FirstReportConfiguration($cem, $calendarManager, $this->getRequest());
        
        //Create your report builder, should be a service...
        $rptBuilder = new ReportBuilder(
            $firstRptConfig, //your report configuration
            //some dependency the report builder needs
            $this->get('service_container'), $this->get('lexik_form_filter.query_builder_updater'), $this->get('request'), $this->get('form.factory')
        );

        //build your report
        $rptBuilder->build();

        //the export manager help you to generate quickly your report and filter, it will give you directly 
        //html or excel
        $exportManager = $this->get('report.template.generator.manager');

        //for example excel, only the report
        if ($this->getRequest()->get('export_report_format') != 'html') {
            //will get xls / pdf ... pdf is not available yet
            $format = $this->getRequest()->get('export_report_format');
            $filename = sprintf('export_%s_%s_%s', 'first_report', date('Y_m_d_H_i_s', strtotime('now')));

            //getReport, give you the object report with all rows/columns and definitions
            return $exportManager->getResponse($format, $filename, $rptBuilder->getReport(), 'firstTable');
        }
        //report
        //will give you all html you need to put directly in your template
        $htmlTable = $exportManager->getResponse('html', null, $rptBuilder->getReport(), 'firstTable');

        //your template
        return $this->render('ProjectReportBundle:Report:firstReport.html.twig', array(
                    'htmlFilter' => $htmlFilter,
                    'htmlTable' => $htmlTable
        ));
    }
}
```

Last things, create your `view` :
```twig
{% extends "::base.html.twig" %}

{% block body %}
    <h1>Sales Report</h1>
    <br>
    {{ htmlFilter|raw }}

    <div class="table-responsive">
        {{ htmlTable|raw }}
    </div>
{% endblock %}
```

The page will display a table like :

Year|Company Results
----|---------------
2014 |$ 120 000.32
2013 |$ 152 569.32
2012 |$ 135 599.59
Total|$ 408 169.23

