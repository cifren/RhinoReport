<?php

namespace Earls\RhinoReportBundle\Report\Definition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 *  Earls\RhinoReportBundle\Report\Definition\ReportDefinition.
 */
class ReportDefinition implements ReportDefinitionInterface
{
    protected $factory;
    protected $items;
    protected $filters;
    protected $template = 'DefaultTemplate';

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->filters = new ArrayCollection();
    }

    public function getItem($displayId)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('displayId', $displayId))
        ;

        $items = $this->items->matching($criteria);
        $item = ($items->count() > 0) ? array_shift(array_values($items->toArray())) : null;

        return $item;
    }

    public function addItem(ModuleDefinitionInterface $item)
    {
        if (!$item->getDisplayId()) {
            throw new \UnexpectedValueException('The Id must not be empty');
        }

        if ($this->getItem($item->getDisplayId())) {
            throw new \UnexpectedValueException('This Id \''.$item->getDisplayId().'\' is already used');
        }

        $item->setParent($this);
        $this->items[] = $item;

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    public function getObjectFactory()
    {
        return $this->factory;
    }

    public function setObjectFactory($factory)
    {
        $this->factory = $factory;

        return $this;
    }

    public function getDisplayId()
    {
        return 'main_report';
    }

    public function addFilter($filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function getFilters()
    {
        return $this->filters;
    }
}
