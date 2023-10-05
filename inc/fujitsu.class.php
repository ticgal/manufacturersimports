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
 * Class PluginManufacturersimportsFujitsu
 */
class PluginManufacturersimportsFujitsu extends PluginManufacturersimportsManufacturer
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
        $info["name"]         = PluginManufacturersimportsConfig::FUJITSU;
        $info["supplier_url"] = 'https://support.ts.fujitsu.com/ProductCheck/Default.aspx?Lng=en&GotoDiv=Warranty/WarrantyStatus&DivID=indexwarranty&GotoUrl=IndexWarranty&RegionID=1&Token=${$i$M$f$u&Ident=';
        $info["url"]          = $supplierUrl.$compSerial;
        $info["url_web"]      = "https://support.ts.fujitsu.com/IndexWarranty.asp?lng=FR";
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
        return ["url" => 'https://support.ts.fujitsu.com/ProductCheck/Default.aspx?Lng=en&GotoDiv=Warranty/WarrantyStatus&DivID=indexwarranty&GotoUrl=IndexWarranty&RegionID=1&Token=${$i$M$f$u&Ident=' . "$compSerial"];
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getBuyDate()
     */
    public function getBuyDate($contents)
    {
        $matchesarray = [];
        preg_match_all("/value=\"(\d{4}\-\d{2}\-\d{2})\" id=\"Firstuse\"/", $contents, $matchesarray);

        $buydate = (isset($matchesarray[1][0])?trim($matchesarray[1][0]):'0000-00-00');

        return $buydate;
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
        $matchesarray = [];
        preg_match_all("/value=\"(\d{4}\-\d{2}\-\d{2})\" id=\"WarrantyEndDate\"/", $contents, $matchesarray);

        $expirationdate = (isset($matchesarray[1][0])?trim($matchesarray[1][0]):'0000-00-00');

        return $expirationdate;
    }
}
