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

/**
 * Class PluginManufacturersimportsMenu
 */
class PluginManufacturersimportsMenu extends CommonGLPI {

   static $rightname = 'plugin_manufacturersimports';

   /**
    * Get menu name
    *
    * @since version 0.85
    *
    * @return character menu shortcut key
    **/
   static function getMenuName() {
      return _n('Suppliers import',
                'Suppliers imports',
                2,
                'manufacturersimports');
   }

   /**
    * get menu content
    *
    * @since version 0.85
    *
    * @return array for menu
    **/
   static function getMenuContent() {
      $plugin_page              = PluginManufacturersimportsImport::getSearchURL(false);
      $menu                     = [];
      //Menu entry in tools
      $menu['title']            = self::getMenuName();
      $menu['page']             = $plugin_page;
      $menu['links']['search']  = $plugin_page;

      if (Session::haveRight(static::$rightname, UPDATE)
            || Session::haveRight("config", UPDATE)) {
         //Entry icon in breadcrumb
         $menu['links']['config']                      = PluginManufacturersimportsConfig::getSearchURL(false);
         //Link to config page in admin plugins list
         $menu['config_page']                          = PluginManufacturersimportsConfig::getSearchURL(false);

         //Add a fourth level in breadcrumb for configuration page
         $menu['options']['config']['title']           = __('Setup');
         $menu['options']['config']['page']            = PluginManufacturersimportsConfig::getSearchURL(false);
         $menu['options']['config']['links']['search'] = PluginManufacturersimportsConfig::getSearchURL(false);
         $menu['options']['config']['links']['add']    = PluginManufacturersimportsConfig::getFormURL(false);
      }

      $menu['icon'] = self::getIcon();

      return $menu;
   }

   static function getIcon() {
      return "ti ti-satellite";
   }

   static function removeRightsFromSession() {
      if (isset($_SESSION['glpimenu']['tools']['types']['PluginManufacturersimportsMenu'])) {
         unset($_SESSION['glpimenu']['tools']['types']['PluginManufacturersimportsMenu']);
      }
      if (isset($_SESSION['glpimenu']['tools']['content']['pluginmanufacturersimportsmenu'])) {
         unset($_SESSION['glpimenu']['tools']['content']['pluginmanufacturersimportsmenu']);
      }
   }
}
