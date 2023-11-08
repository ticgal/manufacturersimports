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

ini_set("max_execution_time", "0");

/**
 * Class PluginManufacturersimportsLenovo
 */
class PluginManufacturersimportsLenovo extends PluginManufacturersimportsManufacturer
{
    /**
     * @see PluginManufacturersimportsManufacturer::showCheckbox()
     */
    public function showCheckbox($ID, $sel, $otherSerial = false): string
    {
        $name = "item[" . $ID . "]";
        return Html::getCheckbox(["name" => $name, "value" => 1, "selected" => $sel]);
    }

    /**
     * @see PluginManufacturersimportsManufacturer::showItemTitle()
     */
    public function showItemTitle($output_type, $header_num): string
    {
        return Search::showHeaderItem($output_type, __('Model number', 'manufacturersimports'), $header_num);
    }

    /**
     * @see PluginManufacturersimportsManufacturer::showDocTitle()
     */
    public function showDocTitle($output_type, $header_num): string
    {
        return Search::showHeaderItem($output_type, __('File'), $header_num);
    }

    /**
     * @see PluginManufacturersimportsManufacturer::showItem()
     */
    public function showItem($output_type, $item_num, $row_num, $otherSerial = false): bool
    {
        return false;
    }

    public function getSearchField(): bool
    {
        return false;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getSupplierInfo()
     */
    public function getSupplierInfo(
        $compSerial = null,
        $otherSerial = null,
        $key = null,
        $apisecret = null,
        $supplierUrl = null
    ): array {
        $info["name"]           = PluginManufacturersimportsConfig::LENOVO;
        $info["supplier_url"]   = "https://pcsupport.lenovo.com/products/$compSerial/warranty";
        //      $info["url"]          = $supplierUrl . $compSerial."?machineType=&btnSubmit";
        $info["url"]            = "https://pcsupport.lenovo.com/products/$compSerial/warranty";
        $info["url_web"]        = "https://pcsupport.lenovo.com/products/$compSerial/warranty";

        return $info;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getBuyDate()
     */
    public function getBuyDate($contents)
    {
        $data_key = "var ds_warranties";
        $field  = "POPDate";

        $chunk = stristr($contents, $data_key);
        preg_match('/\{(?:[^{}]|(?R))*\}/', $chunk, $matches);
        $data = [];
        if (!empty($matches)) {
            $data = json_decode($matches[0], true);
        } else {
            return '';
        }

        $warranties = [];
        if (isset($data['UpmaWarranties'])) {
            foreach ($data['UpmaWarranties'] as $warranty) {
                $warranties[] = $warranty[$field];
            }
        } elseif (isset($data['BaseWarranties'])) {
            foreach ($data['BaseWarranties'] as $warranty) {
                $warranties[] = $warranty[$field];
            }
        } else {
            return '';
        }

        $myDate = PluginManufacturersimportsPostImport::getHigherValue($warranties, 'date');
        $myDate = PluginManufacturersimportsPostImport::checkDate(date('Y-m-d', $myDate));

        return $myDate;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getStartDate()
     */
    public function getStartDate($contents)
    {
        $data_key = "var ds_warranties";
        $field  = "Start";

        $chunk = stristr($contents, $data_key);
        preg_match('/\{(?:[^{}]|(?R))*\}/', $chunk, $matches);
        $data = [];
        if (!empty($matches)) {
            $data = json_decode($matches[0], true);
        } else {
            return '';
        }

        $warranties = [];
        if (isset($data['UpmaWarranties'])) {
            foreach ($data['UpmaWarranties'] as $warranty) {
                $warranties[] = $warranty[$field];
            }
        } elseif (isset($data['BaseWarranties'])) {
            foreach ($data['BaseWarranties'] as $warranty) {
                $warranties[] = $warranty[$field];
            }
        } else {
            return '';
        }

        $myDate = PluginManufacturersimportsPostImport::getHigherValue($warranties, 'date');
        $myDate = PluginManufacturersimportsPostImport::checkDate(date('Y-m-d', $myDate));

        return $myDate;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getExpirationDate()
     */
    public function getExpirationDate($contents)
    {
        $data_key = "var ds_warranties";
        $field  = "End";

        $chunk = stristr($contents, $data_key);
        preg_match('/\{(?:[^{}]|(?R))*\}/', $chunk, $matches);
        $data = [];
        if (!empty($matches)) {
            $data = json_decode($matches[0], true);
        } else {
            return '';
        }

        $warranties = [];
        if (isset($data['UpmaWarranties'])) {
            foreach ($data['UpmaWarranties'] as $warranty) {
                $warranties[] = $warranty[$field];
            }
        } elseif (isset($data['BaseWarranties'])) {
            foreach ($data['BaseWarranties'] as $warranty) {
                $warranties[] = $warranty[$field];
            }
        } else {
            return '';
        }

        $myDate = PluginManufacturersimportsPostImport::getHigherValue($warranties, 'date');
        $myDate = PluginManufacturersimportsPostImport::checkDate(date('Y-m-d', $myDate));

        return $myDate;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getWarrantyInfo()
     */
    public function getWarrantyInfo($contents)
    {
        $data_key = "var ds_warranties";
        $field  = "End";

        $chunk = stristr($contents, $data_key);
        preg_match('/\{(?:[^{}]|(?R))*\}/', $chunk, $matches);
        $data = [];
        if (!empty($matches)) {
            $data = json_decode($matches[0], true);
        } else {
            return '';
        }

        $warranties = [];
        $infos = [];
        $info = '';
        if (isset($data['UpmaWarranties'])) {
            foreach ($data['UpmaWarranties'] as $warranty) {
                $warranties[] = $warranty[$field];
                $infos[] = $warranty['Name'];
            }
        } elseif (isset($data['BaseWarranties'])) {
            foreach ($data['BaseWarranties'] as $warranty) {
                $warranties[] = $warranty[$field];
                $infos[] = $warranty['Name'];
            }
        } else {
            return '';
        }

        $pos = PluginManufacturersimportsPostImport::getHigherValue($warranties, 'date', true);
        if ($pos !== false) {
            $info = $infos[$pos];
        }

        return $info;
    }
}
