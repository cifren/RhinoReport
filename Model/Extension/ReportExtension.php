<?php
namespace Earls\RhinoReportBundle\Model\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
/*
 *
 * Earls\RhinoReportBundle\Model\Extension\ReportExtension
 *
 */

class ReportExtension
{
    private $container;
    private $actionOnColumnServiceIds;
    private $groupOnColumnActionServiceIds;
    private $actionOnRowServiceIds;
    private $groupActionOnRowServiceIds;
    private $actionOnGroupServiceIds;
    private $groupActionOnGroupServiceIds;

    public function __construct(ContainerInterface $container, array $actionOnColumnServiceIds, array $groupOnColumnActionServiceIds, array $actionOnRowServiceIds, array $groupActionOnRowServiceIds,
            array $actionOnGroupServiceIds, array $groupActionOnGroupServiceIds)
    {
        $this->container = $container;
        $this->actionOnColumnServiceIds = $actionOnColumnServiceIds;
        $this->groupOnColumnActionServiceIds = $groupOnColumnActionServiceIds;
        $this->actionOnRowServiceIds = $actionOnRowServiceIds;
        $this->groupActionOnRowServiceIds = $groupActionOnRowServiceIds;
        $this->actionOnGroupServiceIds = $actionOnGroupServiceIds;
        $this->groupActionOnGroupServiceIds = $groupActionOnGroupServiceIds;
    }

    public function getTableActionOnColumn($name)
    {
        if (!isset($this->actionOnColumnServiceIds[$name])) {
            throw new \InvalidArgumentException(sprintf('The field Action "%s" is not registered with the service container.', $name));
        }

        return $this->container->get($this->actionOnColumnServiceIds[$name]);
    }

    public function getTableGroupActionOnColumn($name)
    {
        if (!isset($this->groupOnColumnActionServiceIds[$name])) {
            throw new \InvalidArgumentException(sprintf('The field GroupAction "%s" is not registered with the service container.', $name));
        }

        return $this->container->get($this->groupOnColumnActionServiceIds[$name]);
    }

    public function getTableActionOnRow($name)
    {
        if (!isset($this->actionOnRowServiceIds[$name])) {
            throw new \InvalidArgumentException(sprintf('The field Action "%s" is not registered with the service container.', $name));
        }

        return $this->container->get($this->actionOnRowServiceIds[$name]);
    }

    public function getTableGroupActionOnRow($name)
    {
        if (!isset($this->groupActionOnRowServiceIds[$name])) {
            throw new \InvalidArgumentException(sprintf('The field GroupAction "%s" is not registered with the service container.', $name));
        }

        return $this->container->get($this->rowGroupActionServiceIds[$name]);
    }

    public function getTableActionOnGroup($name)
    {
        if (!isset($this->actionOnGroupServiceIds[$name])) {
            throw new \InvalidArgumentException(sprintf('The field Action "%s" is not registered with the service container.', $name));
        }

        return $this->container->get($this->actionOnGroupServiceIds[$name]);
    }

    public function getTableGroupActionOnGroup($name)
    {
        if (!isset($this->groupActionOnGroupServiceIds[$name])) {
            throw new \InvalidArgumentException(sprintf('The field GroupAction "%s" is not registered with the service container.', $name));
        }

        return $this->container->get($this->groupActionOnGroupServiceIds[$name]);
    }
}
