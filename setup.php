<?php

/**
 * -------------------------------------------------------------------------
 * Manufacturersimports plugin for GLPI
 * Copyright (C) 2009-2022 by the Manufacturersimports Development Team.
 *
 * https://github.com/InfotelGLPI/manufacturersimports
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Manufacturersimports.
 *
 * Manufacturersimports is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Manufacturersimports is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Manufacturersimports. If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------------
 *
 * -------------------------------------------------------------------------
 * Manufacturersimports plugin for GLPI
 * Copyright (C) 2023 by the TICgal Team.
 * https://www.tic.gal/
 * -------------------------------------------------------------------------
 * LICENSE
 * This file is part of the Manufacturersimports plugin.
 * Manufacturersimports plugin is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * Manufacturersimports plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Manufacturersimports. If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------------
 * @package   Manufacturersimports
 * @author    the TICgal team
 * @copyright Copyright (c) 2023 TICgal team
 * @license   AGPL License 3.0 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      https://www.tic.gal/
 * @since     2023
 * ----------------------------------------------------------------------
 */

use Glpi\Plugin\Hooks;

define('PLUGIN_MANUFACTURERSIMPORTS_VERSION', '3.2.0');
define('PLUGIN_MANUFACTURERSIMPORTS_MIN_GLPI', '10.0');
define('PLUGIN_MANUFACTURERSIMPORTS_MAX_GLPI', '11.0');
define('PLUGIN_MANUFACTURERSIMPORTS_NAME', 'manufacturersimports');

if (!defined("PLUGIN_MANUFACTURERSIMPORTS_DIR")) {
    define("PLUGIN_MANUFACTURERSIMPORTS_DIR", Plugin::getPhpDir("manufacturersimports"));
    define("PLUGIN_MANUFACTURERSIMPORTS_NOTFULL_DIR", Plugin::getPhpDir("manufacturersimports", false));
    define("PLUGIN_MANUFACTURERSIMPORTS_WEBDIR", Plugin::getWebDir("manufacturersimports"));
}

// Init the hooks of the plugins -Needed
function plugin_init_manufacturersimports()
{
    global $PLUGIN_HOOKS, $CFG_GLPI;

    $PLUGIN_HOOKS['csrf_compliant']['manufacturersimports'] = true;

    if (
        Plugin::isPluginActive('manufacturersimports')
        && Session::getLoginUserID()
    ) {
        // Classes
        Plugin::registerClass(PluginManufacturersimportsProfile::class, ['addtabon' => 'Profile']);

        // Hooks

        // End init, when all types are registered
        $PLUGIN_HOOKS[Hooks::POST_INIT]['manufacturersimports'] = 'plugin_manufacturersimports_postinit';

        $PLUGIN_HOOKS[Hooks::INFOCOM]['manufacturersimports'] = [
            PluginManufacturersimportsConfig::class, 'showForInfocom'
        ];

        $PLUGIN_HOOKS[Hooks::PRE_SHOW_ITEM]['manufacturersimports'] = [
            PluginManufacturersimportsConfig::class, 'showItemImport'
        ];

        //Display menu entry only if user has right to see it !
        if (Session::haveRight('plugin_manufacturersimports', READ)) {
            $PLUGIN_HOOKS["menu_toadd"]['manufacturersimports'] = [
                'tools' => 'PluginManufacturersimportsMenu'
            ];
        }

        if (Session::haveRight('config', UPDATE)) {
            $PLUGIN_HOOKS['config_page']['manufacturersimports'] = 'front/config.php';
            $PLUGIN_HOOKS['use_massive_action']['manufacturersimports'] = 1;
        }
    }

    if (
        isset($_SESSION['glpiactiveprofile']['interface'])
        && $_SESSION['glpiactiveprofile']['interface'] == 'central'
    ) {
        // Add specific files to add to the header : javascript or css
        $PLUGIN_HOOKS[Hooks::ADD_CSS]['manufacturersimports'] = [
            "manufacturersimports.css",
        ];
    }
}

// Get the name and the version of the plugin - Needed
function plugin_version_manufacturersimports()
{
    return [
        'name'          => _n('Suppliers import', 'Suppliers imports', 2, 'manufacturersimports'),
        'oldname'       => 'suppliertag',
        'version'       => PLUGIN_MANUFACTURERSIMPORTS_VERSION,
        'license'       => 'GPLv2+',
        'author'        => "<a href='http://infotel.com/services/expertise-technique/glpi/'>Infotel</a>",
        'homepage'      => 'https://github.com/InfotelGLPI/manufacturersimports/',
        'requirements'  => [
            'glpi' => [
                'min' => PLUGIN_MANUFACTURERSIMPORTS_MIN_GLPI,
                'max' => PLUGIN_MANUFACTURERSIMPORTS_MAX_GLPI,
                'dev' => false
            ],
            'php'  => [
                'exts' => ['soap', 'curl', 'json'],
            ]
        ]
    ];
}
