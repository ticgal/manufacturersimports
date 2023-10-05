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
 * Class PluginManufacturersimportsToshiba
 */
class PluginManufacturersimportsToshiba extends PluginManufacturersimportsManufacturer
{
    /**
     * @see PluginManufacturersimportsManufacturer::showDocTitle()
     */
    public function showDocTitle($output_type, $header_num)
    {
        return Search::showHeaderItem($output_type, __('File'), $header_num);
    }

    public function getSearchField()
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
    )
    {
        $info["name"]         = PluginManufacturersimportsConfig::TOSHIBA;
        $info["supplier_url"] = "https://support.dynabook.com/support/warrantyResults?";
        $info["url"]          = $supplierUrl .
                                "sno=" . $compSerial;
        return $info;
    }

    /**
     * Summary of getWarrantyUrl
     *
     * @param  $config
     * @param  $compSerial
     *
     * @return string[]
     */
    public static function getWarrantyUrl($config, $compSerial)
    {
        return ["url" => "https://support.dynabook.com/support/warrantyResults?sno=" . "$compSerial"];
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getBuyDate()
     */
    public function getBuyDate($contents)
    {
        $field  = "shipDate";
        $search = stristr($contents, $field);
        $myDate = substr($search, 11, 21);
        $myDate = trim($myDate);
        $myDate = PluginManufacturersimportsPostImport::checkDate($myDate);

        return $myDate;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getStartDate()
     */
    public function getStartDate($contents)
    {
        $field  = "customerPurchaseDate";
        $search = stristr($contents, $field);
        $myDate = substr($search, 23, 21);
        $myDate = trim($myDate);
        $myDate = PluginManufacturersimportsPostImport::checkDate($myDate);

        return $myDate;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getExpirationDate()
     */
    public function getExpirationDate($contents)
    {
        $field     = "warrantyExpiryDate";
        $search    = stristr($contents, $field);
        $myEndDate = substr($search, 21, 21);
        $myEndDate = trim($myEndDate);
        $myEndDate = PluginManufacturersimportsPostImport::checkDate($myEndDate);
        return $myEndDate;
    }
}
