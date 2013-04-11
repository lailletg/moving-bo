<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return array(
    'asset_manager' => array(
        'resolver_configs' => array(
            'collections' => array(
                'js/bootstrap-all.js' => array(
                    'js/jquery.js',
                    'js/bootstrap.js',
                ),
                'css/bootstrap-all.css' => array(
                    'css/bootstrap-all-less.css',
                ),
                'css/bootstrap-all-less.css' => array(
                    'css/bootstrap.css',
                    'css/bootstrap-responsive.css',
                ),
            ),
            'paths' => array(
                __DIR__ . '/../../public',
            ),
            'map' => array(
                'css/bootstrap.css' => __DIR__ . '/../../public/less/bootstrap.less',
                'css/bootstrap-responsive.css' => __DIR__ . '/../../public/less/responsive.less',
            ),
        ),
        'filters' => array(
            'css/bootstrap-all-less.css' => array(
                array(
                    'filter' => 'Lessphp',
                ),
            ),/*
            'js/bootstrap-all.js' => array(
                array(
                    'filter' => 'JSMin',
                ),
            ),*/

        ),
        'caching' => array(
            'default' => array(
                'Apc'     => 'Filesystem',
            ),
        ),
    ),
);