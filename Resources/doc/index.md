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
composer.phar update earls/earls/rhino-report-bundle # to only update the bundle
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

Usage
=====

Here a simple example of `report` creation

The structure of our example :

```
-- src/
---- Project/
------ ReportBundle/
-------- Config/
---------- FirstReport.php
-------- Controller/
---------- ReportController.php
-------- Filter/
---------- FirstReportFilterType.php
-------- Resources
---------- views
------------ Report
-------------- FirstReport.html.twig
```

First file will be a `filterType` from [LexikFormBundle](https://github.com/lexik/LexikFormFilterBundle/blob/v1.1.1/Resources/doc/index.md), the installation of this bundle is done automatically by `Composer` via `RhinoReportBundle` dependencies.
This bundle give the possibility to create quickly a `form` + `criteria` option like "Contains", "not contain" for character chain for example but as well the possibility to combine a `queryBuilder` with the form.

Create your `filter` :
```php
namespace Project\ReportBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Earls\RhinoReportBundle\Filter\ReportFilterInterface;

class FirstReportConfiguration extends AbstractType implements ReportFilterInterface
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('sale_date', 'filter_date_range')
                ->add('category', 'filter_entity', array(
                    'class' => 'category',
                    'property' => 'displayName'
                    ))
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'csrf_protection' => false,
        );
    }


    public function getName()
    {
        return 'first_report_filter';
    }

}
```

Create your `configuration file` :
```php
namespace Project\ReportBundle\Configuration;

use Earls\RhinoReportBundle\Definition\Table\TableDefinitionBuilder;
use Earls\RhinoReportBundle\Definition\ReportConfiguration;
use Doctrine\ORM\EntityManager;

/**
 *  Project\ReportBundle\Configuration\FirstReportConfiguration
 */
class FirstReportConfiguration extends ReportConfiguration
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getConfigReportDefinition(Request $request, $dataFilter)
    {
        $definition = new ReportDefinitionBuilder();
        $tableDefinitionHeader =  $this->getTableDefintionHeader($dataFilter);

        //name of your table
        $definition->table('table')
            //attribute for the table, can be html / excel, here used for bootstrap classes
            ->attr(array('class' => array('header-fixed', 'table-bordered', 'table-condensed')))
                    //list of your column in your table, tag "thead > th" in html
                    ->head()
                        ->headColumns(array(
                            'id' => 'id',
                            'desc' => 'Name item',
                            'sold' => 'Nb of item sold',
                            'sales' => 'Nb of sales',
                            )
                        )
                    ->end()//head
                    //where all your data will be manage to end in the display
                    ->body()
                        ->group('catGroup')
                            ->groupBy('catId')
                            ->rowUnique()
                                ->column('id', 'catId')
                                ->column('desc', 'catDescr')
                                ->column('sold')
                                    //action on a group, give you the possibility to make the sum of 
                                    //all your items sold
                                    ->groupAction('sum', array('column' => '\table\body\catGroup\salesGroup.sold'))
                                ->end()//column
                                ->column('sales')
                                    //action on a group, give you the possibility to make the sum of all your 
                                    //sales, means price * item sold
                                    ->groupAction('sum', array('column' => '\table\body\catGroup\salesGroup.sales'))
                                ->end()//column
                            ->end()//row
                            ->group('salesGroup')
                                ->groupBy('itemId')
                                ->row()
                                    ->column('id', 'itemId')
                                    ->column('desc', 'itemDesc')
                                    ->column('sold', 'itemSold')
                                    //column data is a temporary column, used in the configuration and the creation of 
                                    //your report Object but not displayed
                                    ->columnData('priceData', 'itemPrice')
                                    ->column('sales')
                                        //this action will calculate quickly price * item sold, it only needs some arguments
                                        ->action('calculation', array(
                                            'formula' => '%1$s * %2$s',
                                            'arg_displayIds' => array('priceData', 'sales')
                                        ))
                                        //this action will round the result, use php function round
                                        ->action('round', array('precision' => 2))
                                        //this action will transform '2' into '$2', use php function money_format
                                        ->action('money_format', array('displayId' => 'sales'))
                                    ->end()//column
                                ->end()//row
                            ->end()//group
                        ->end()//group
                        ->rowUnique()
                            ->column('id')
                                //will display static information
                                ->action('label_field', array('label' => 'Total'))
                            ->end()//column
                            ->columnSpan('#', 3)
                            ->column('desc')->end()//column
                            ->column('sold')->end()//column
                            ->column('sales')
                                ->groupAction('sum', array('column' => '\table\body\catGroup.sales'))
                            ->end()//column
                        ->end()//row
                    ->end()//Body
                ->end()//table
                
        return $definition->build();
                            
    }

    public function getFilter()
    {
        return new FirstReportFilterType();
    }

    public function getQueryBuilder(){
        $qb = $this->em->createQueryBuilder()
            ->select('s')
            ->from('sales', 's');

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
            $firstRptConfig, 
            $this->get('service_container'), $this->get('lexik_form_filter.query_builder_updater'), $this->get('request'), $this->get('form.factory'));

        //if no filter no report
        if ($this->getRequest()->get('first_report_filter')) {
            //build your report
            $rptBuilder->build();

            //the export manager help you to generate quickly your report and filter, it will give you directly html or excel
            $exportManager = $this->get('report.template.generator.manager');

            //for example excel, only the report
            if ($this->getRequest()->get('export_report_format') != 'html') {
                //will get xls / pdf ... pdf is not available yet
                $format = $this->getRequest()->get('export_report_format');
                $filename = sprintf('export_%s_%s_%s', 'first_report', date('Y_m_d_H_i_s', strtotime('now')));
                
                //getReport, give you the object report with all rows/columns and definitions
                return $exportManager->getResponse($format, $filename, $rptBuilder->getReport(), 'table');
            }
            //report + filter
            //will give you all html you need to put directly in your template
            $htmlFilter = $exportManager->getResponse('html', null, $rptBuilder->getReport(), 'filter');
            $htmlTable = $exportManager->getResponse('html', null, $rptBuilder->getReport(), 'table');
        } else {
            //only the filter
            $rptBuilder->buildFilter();
            $exportManager = $this->get('report.template.generator.manager');

            $htmlFilter = $exportManager->getResponse('html', null, $rptBuilder->getReport(), 'filter');
            $htmlTable = null;
        }

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
