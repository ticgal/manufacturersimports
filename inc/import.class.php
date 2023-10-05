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

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginManufacturersimportsImport
 */
class PluginManufacturersimportsImport extends CommonDBTM
{

    /**
     * @param $name
     *
     * @return array
     */
    public static function cronInfo($name)
    {
        switch ($name) {
            case "DataWarrantyImport":
                return ['description' => PluginManufacturersimportsModel::getTypeName(1) . " - " . __('Warranty import (Dell, HP)', 'manufacturersimports')];
        }
        return [];
    }


    /**
     * Run for data recovery warranties
     *
     * @param $task : object of crontask
     *
     * @return integer : 0 (nothing to do)
     *                   >0 (endded)
     **/
    public static function cronDataWarrantyImport($task)
    {
        $cron_status = PluginManufacturersimportsImport::importCron($task, PluginManufacturersimportsConfig::DELL);

        $cron_status = PluginManufacturersimportsImport::importCron($task, PluginManufacturersimportsConfig::HP);

        return $cron_status;
    }
    /**
     * Import via le cron
     *
     * @param type  $supplier
     *
     * @return int
     * @global type $DB
     *
     */
    public static function importCron($task, $supplier)
    {
        global $DB;

        $config = new PluginManufacturersimportsConfig();
        $config->getFromDBByCrit(['name' => $supplier]);

        $log = new PluginManufacturersimportsLog();

        $suppliername = $config->fields["name"];
        $supplierUrl  = $config->fields["supplier_url"];
        $supplierkey  = $config->fields["supplier_key"];
        $supplierId   = $config->fields["suppliers_id"];

        $toview = ["name" => 1];

        $params                     = [];
        $params['manufacturers_id'] = $config->getID();
        $params['imported']         = PluginManufacturersimportsPreImport::NOT_IMPORTED;
        $params['sort']             = 1;
        $params['order']            = "ASC";
        $params['start']            = 0;

      //      $types = PluginManufacturersimportsConfig::getTypes();

        $nb_import_error = 0;
        $msg             = "";

      //      foreach ($types as $type) {
        $type               = "Computer";
        $params['itemtype'] = $type;
        $query              = PluginManufacturersimportsPreImport::queryImport($params, $config, $toview, true);

        $result = $DB->query($query);

        if ($DB->numrows($result) > 0) {
            while ($data = $DB->fetchArray($result)) {
                $log->reinitializeImport($type, $data['id']);

                $compSerial = $data['serial'];
                $ID         = $data['id'];

                $model       = new PluginManufacturersimportsModel();
                $otherSerial = $model->checkIfModelNeeds($type, $ID);

                $url  = PluginManufacturersimportsPreImport::selectSupplier(
                    $suppliername,
                    $supplierUrl,
                    $compSerial,
                    $otherSerial,
                    $supplierkey
                );
                $post = PluginManufacturersimportsPreImport::getSupplierPost(
                    $suppliername,
                    $compSerial,
                    $otherSerial
                );

                $options = ["url"     => $url,
                            "post"    => $post,
                            "type"    => $type,
                            "ID"      => $ID,
                            "config"  => $config,
                            "line"    => $data,
                            "display" => false];

                if ($suppliername == PluginManufacturersimportsConfig::DELL) {
                    $supplierclass    = "PluginManufacturersimports" . $suppliername;
                    $token            = $supplierclass::getToken($config);
                    $warranty_url     = $supplierclass::getWarrantyUrl($config, $compSerial);
                    $options['token'] = $token;
                    if (isset($warranty_url)) {
                        $options['url'] = $warranty_url['url'];
                    }
                    if (PluginManufacturersimportsPostImport::saveImport($options)) {
                        $task->addVolume(1);
                    } else {
                        $nb_import_error += 1;
                    }
                }
                if ($suppliername == PluginManufacturersimportsConfig::HP) {
                    $supplierclass    = "PluginManufacturersimports" . $suppliername;
                    $warranty_url     = $supplierclass::getWarrantyUrl($config, $compSerial);
                    if (isset($warranty_url)) {
                        $options['url'] = $warranty_url['url'];
                    }
                    if (PluginManufacturersimportsPostImport::saveImport($options)) {
                        $task->addVolume(1);
                    } else {
                        $nb_import_error += 1;
                    }
                }
            }
        }

      //      }
        if ($task) {
            $task->log(__('Import OK', 'manufacturersimports'));

            $task->addVolume($nb_import_error);
            $task->log(__('Import failed', 'manufacturersimports'));
        }
        return true;
    }
}
