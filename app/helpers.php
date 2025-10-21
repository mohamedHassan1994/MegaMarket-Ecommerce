<?php

if (!function_exists('isActive')) {
    function isActive($routes, $class = 'active') {
        foreach ((array) $routes as $route) {
            if (request()->is($route)) {
                return $class;
            }
        }
        return '';
    }
}
