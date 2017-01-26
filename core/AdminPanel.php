<?php

namespace Heidi\Core;

use Heidi\Core\AdminPanelRow;

class AdminPanel
{
    public $schema;

    public function __construct($schema)
    {
        $this->schema = $schema;
    }

    public static function addMetaBoxes(BaseOption $class, $screen, $context = 'normal', $canAdd = false)
    {
        if(! empty($class->value)) {

            foreach($class->value as $index => $option)
            {
                $boxName = $class->name;
                if(isset($option['label']))
                    $boxName = $option['label'];

                $boxId = $class->slug . '-' . $index;

                add_meta_box(
                    $boxId,
                    $boxName,
                    [new static($class->schema), 'render'],
                    $screen,
                    $context,
                    'default',
                    [$option, $index]
                );

                if($canAdd)
                {
                    add_filter( "postbox_classes_{$screen}_{$boxId}", [new static($class->schema), "addMetaboxClasses"] );
                }

            }

        } else {

            add_meta_box(
                $class->slug . '-0',
                $class->name,
                [new static($class->schema), 'render'],
                $screen,
                $context,
                'high',
                [null, 0]
            );

            if($canAdd)
            {
                add_filter( "postbox_classes_{$screen}_0", [new static($class->schema), "addMetaboxClasses"] );
            }
        }
        if($canAdd)
        {
            add_meta_box(
                'add-more-panel',
                'Add More',
                [new static($class->schema), 'renderAddMore'],
                $screen,
                $context,
                'default'
            );
        }
    }

    public function render($post, Array $callback_args)
    {
        $option = $callback_args['args'][0];

        $index = $callback_args['args'][1];

        $panel = $this;

        $schema = $this->getSchema($this->schema);

        foreach($schema as $row)
        {

            $value = isset($option[$row->schema]) ? $option[$row->schema] : '';

            $panel->rows[] = new AdminPanelRow($row, $value, $index, $this->schema);

        }

        view('core.admin_panel', compact('panel'));
    }

    public function addMetaboxClasses($classes = [])
    {
        $classes[] = sanitize_html_class('q4vr-admin-panel-can-add');

        return $classes;
    }

    public function renderAddMore()
    {
        $schema = $this->schema;

        view('core.admin_panel.add_more', compact('schema'));
    }

    private function getSchema($file)
    {
        $schema = file_get_contents(Heidi_RESOURCE_PATH . 'schemas/' . $file . '.json');

        return json_decode($schema);
    }
}
