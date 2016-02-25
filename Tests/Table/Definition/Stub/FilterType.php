<?php

namespace Earls\RhinoReportBundle\Tests\Table\Definition\Stub;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Earls\RhinoReportBundle\Report\Filter\ReportFilterInterface;

/**
 * Pp3\ReportBundle\Filter\FilterType
 */
class FilterType extends AbstractType implements ReportFilterInterface
{

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('cat', 'Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType', array(
                    'attr' => array('class' => 'form-control input-sm'),
                    'choices' => array('All','Food', 'Liquor'),
                    'label' => 'Category',
                    'empty_data' => null
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'stub_filter';
    }

    public function getDefaultBind()
    {
        return array();
    }

}
