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
 * Class PluginManufacturersimportsWortmann_ag
 */
class PluginManufacturersimportsWortmann_ag extends PluginManufacturersimportsManufacturer
{
    /**
     * @see PluginManufacturersimportsManufacturer::showDocTitle()
     */
    public function showDocTitle($output_type, $header_num)
    {
        return false;
    }

    public function getSearchField()
    {
        return "search";
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
        $info["name"]         = PluginManufacturersimportsConfig::WORTMANN_AG;
        $info["supplier_url"] = "https://www.wortmann.de/fr-fr/profile/snsearch.aspx?SN=";
        $info["url"]          = $supplierUrl.$compSerial;
        return $info;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getBuyDate()
     */
    public function getBuyDate($contents)
    {
        $field     = "but de la garantie";
        $searchstart = stristr($contents, $field);
        $myDate = substr($searchstart, 27, 10);

        $myDate = trim($myDate);
        $myDate = str_replace('/', '-', $myDate);

        $myDate = PluginManufacturersimportsPostImport::checkDate($myDate, true);

        if ($myDate != "0000-00-00") {
            list($day, $month, $year) = explode('-', $myDate);
            $myDate = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));
        }

        return $myDate;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getStartDate()
     */
    public function getStartDate($contents)
    {
        return self::getBuyDate($contents);
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getExpirationDate()
     */
    public function getExpirationDate($contents)
    {
        $field     = "Fin de garantie";
        $searchstart = stristr($contents, $field);
        $myDate = substr($searchstart, 24, 10);

        $myDate = trim($myDate);
        $myDate = str_replace('/', '-', $myDate);

        $myDate = PluginManufacturersimportsPostImport::checkDate($myDate, true);

        if ($myDate != "0000-00-00") {
            list($day, $month, $year) = explode('-', $myDate);
            $myDate = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));
        }
        return $myDate;
    }
}
