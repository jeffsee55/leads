<?php

namespace Heidi\Plugin;

class Plugin
{
    protected static $singleInstance = null;

    public static function getInstance()
    {
        if(null === self::$singleInstance)
        {
            self::$singleInstance = new self();
        }

        return self::$singleInstance;
    }

}
