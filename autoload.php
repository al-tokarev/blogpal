<?php

    function my_autoloader($classname) {
        require_once 'entities/' . $classname . '.php';
    }

    spl_autoload_register('my_autoloader');
