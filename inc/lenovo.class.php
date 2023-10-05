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
class PluginManufacturersimportsLenovo extends PluginManufacturersimportsManufacturer {

    /**
     * @see PluginManufacturersimportsManufacturer::showCheckbox()
     */
    function showCheckbox($ID, $sel, $otherSerial = false) {
        $name = "item[" . $ID . "]";
        return Html::getCheckbox(["name" => $name, "value" => 1, "selected" => $sel]);

    }

    /**
     * @see PluginManufacturersimportsManufacturer::showItemTitle()
     */
    function showItemTitle($output_type, $header_num) {
        return Search::showHeaderItem($output_type, __('Model number', 'manufacturersimports'), $header_num);
    }

    /**
     * @see PluginManufacturersimportsManufacturer::showDocTitle()
     */
    function showDocTitle($output_type, $header_num) {
        return Search::showHeaderItem($output_type, __('File'), $header_num);
    }

    /**
     * @see PluginManufacturersimportsManufacturer::showItem()
     */
    function showItem($output_type, $item_num, $row_num, $otherSerial = false) {
        return false;
    }

    function getSearchField() {
        return false;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getSupplierInfo()
     */
    function getSupplierInfo($compSerial = null, $otherSerial = null, $key = null, $apisecret = null,
                             $supplierUrl = null) {

        $info["name"]         = PluginManufacturersimportsConfig::LENOVO;
        $info["supplier_url"] = "https://pcsupport.lenovo.com/products/$compSerial/warranty";
        //      $info["url"]          = $supplierUrl . $compSerial."?machineType=&btnSubmit";
        $info["url"]     = "https://pcsupport.lenovo.com/products/$compSerial/warranty";
        $info["url_web"] = "https://pcsupport.lenovo.com/products/$compSerial/warranty";
        return $info;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getBuyDate()
     */
    function getBuyDate($contents) {

//        $contents = json_decode($contents, true);
        $field  = "POPDate";
        $search = stristr($contents, $field);
        $myDate = substr($search, 10, 10);
        $myDate = trim($myDate);
        $myDate = PluginManufacturersimportsPostImport::checkDate($myDate);

        return $myDate;

//        if (isset($contents['POPDate'])) {
//
//            Toolbox::loginfo($contents['POPDate']);
//
//            if (strpos($contents['POPDate'], '0001-01-01') !== false) {
//                if (strpos($contents['Shipped'], '0001-01-01') !== false) {
//                    if (isset($contents['Warranty']) && !empty($contents['Warranty'])) {
//                        $minStart = 0;
//                        $start    = 0;
//                        $n        = 0;
//                        foreach ($contents['Warranty'] as $id => $warranty) {
//                            $myDate    = trim($warranty['start']);
//                            $dateStart = strtotime($myDate);
//                            if ($n === 0) {
//                                $minStart = $dateStart;
//                                $myDate   = strtotime(trim($warranty['Start']));
//                            }
//                            if ($dateStart > $minStart) {
//                                $minStart = $dateStart;
//                                $myDate   = strtotime(trim($warranty['Start']));
//                            }
//                            $n++;
//                        }
//                    }
//                } else {
//                    $myDate = trim($contents['POPDate']);
//                }
//            } else {
//                $myDate = trim($contents['POPDate']);
//            }
//            Toolbox::loginfo($myDate);
//            //         $myDate = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));
//            $myDate = date("Y-m-d", strtotime($myDate));
//
//
//            return PluginManufacturersimportsPostImport::checkDate($myDate);
//        }
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getStartDate()
     */
    function getStartDate($contents) {

        $field  = "POPDate";
        $search = stristr($contents, $field);
        $myDate = substr($search, 10, 10);
        $myDate = trim($myDate);
        $myDate = PluginManufacturersimportsPostImport::checkDate($myDate);

        return $myDate;

//        //TODO change to have good start date with new json
//        $contents = json_decode($contents, true);
//        if (isset($contents['Warranty']) && !empty($contents['Warranty'])) {
//            $maxEnd = 0;
//            $start  = 0;
//            foreach ($contents['Warranty'] as $id => $warranty) {
//                $myDate  = trim($warranty['End']);
//                $dateEnd = strtotime($myDate);
//                if ($dateEnd > $maxEnd) {
//                    $maxEnd = $dateEnd;
//                    $start  = strtotime(trim($warranty['Start']));
//                }
//            }
//
//        }
//
//        if (isset($start)) {
//            $myDate = date("Y-m-d", $start);
//
//            return PluginManufacturersimportsPostImport::checkDate($myDate);
//        }

    }

    /**
     * @see PluginManufacturersimportsManufacturer::getExpirationDate()
     */
    function getExpirationDate($contents) {
//        $contents = json_decode($contents, true);
//        //TODO change to have good expiration date with new json
//        if (isset($contents['Warranty']) && !empty($contents['Warranty'])) {
//            $maxEnd = 0;
//
//            foreach ($contents['Warranty'] as $id => $warranty) {
//                $myDate  = trim($warranty['End']);
//                $dateEnd = strtotime($myDate);
//                if ($dateEnd > $maxEnd) {
//                    $maxEnd = $dateEnd;
//                }
//            }
//
//        }
//
//        if (isset($maxEnd)) {
//            $myDate = date("Y-m-d", $maxEnd);
//
//            return PluginManufacturersimportsPostImport::checkDate($myDate);
//        }

        $field     = "BaseUpmaWarranties";
        $search    = stristr($contents, $field);

        $myEndDate = substr($search, 29, 10);

        $myEndDate = trim($myEndDate);
        $myEndDate = PluginManufacturersimportsPostImport::checkDate($myEndDate);
        return $myEndDate;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getWarrantyInfo()
     */
    function getWarrantyInfo($contents) {
//        $contents = json_decode($contents, true);
//
//        //TODO change to have good information with new json
//        $warranty_info = false;
//        if (isset($contents['Warranty']) && !empty($contents['Warranty'])) {
//            $maxEnd = 0;
//
//            foreach ($contents['Warranty'] as $id => $warranty) {
//                $myDate  = trim($warranty['End']);
//                $dateEnd = strtotime($myDate);
//                if ($dateEnd > $maxEnd) {
//                    $maxEnd = $dateEnd;
//                    if (isset($warranty["Description"])) {
//                        $warranty_info = $warranty["Description"];
//                    } else {
//                        $warranty_info = $warranty["Type"] . " - " . $warranty["Name"];
//                    }
//                }
//            }
//
//        }
//        if (strlen($warranty_info) > 255) {
//            $warranty_info = substr($warranty_info, 0, 254);
//        }
//        return $warranty_info;
    }
}
