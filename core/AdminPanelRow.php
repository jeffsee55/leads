<?php

namespace Heidi\Core;

class AdminPanelRow
{
    public $name;

    public $option;

    public $type;

    public $value;

    public $arguments = [];

    public $argumentOptions = [];

    public function __construct(\StdClass $row, $value, $index, $schema)
    {
        $this->name = $row->name;

        $this->option = $this->setOption($schema, $row->schema, $index);

        $this->type = $row->type;

        $this->value = $value;

        $this->class = $this->getClass($row, $value);

        if(property_exists($row, 'arguments'))
            $this->arguments = $row->arguments;

        if(property_exists($row, 'argument_options'))
            $this->argumentOptions = $row->argument_options;
    }

    public function layout()
    {
        $row = $this;
        return view('core.admin_panel.' . strtolower($this->type) . '_input', compact('row'));
    }

    public function setOption($baseSchema, $schema, $index)
    {
        return "{$baseSchema}[{$index}][{$schema}]";
    }

    public function getClass($row, $value)
    {
        $class = $row->schema;

        if($row->schema != 'arguments')
            return $class;

        if($value)
        {
            if(empty(array_filter($value)))
                $class .= ' hidden';
        }

        return $class;
    }
}
