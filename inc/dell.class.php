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
 * Class PluginManufacturersimportsDell
 */
class PluginManufacturersimportsDell extends PluginManufacturersimportsManufacturer
{
    /**
     * @see PluginManufacturersimportsManufacturer::showCheckbox()
     */
    public function showCheckbox($ID, $sel, $otherSerial = false)
    {
        $name = "item[" . $ID . "]";
        return Html::getCheckbox(["name" => $name, "value" => 1, "selected" => $sel]);
    }

    /**
     * @see PluginManufacturersimportsManufacturer::showItem()
     */
    public function showItem($output_type, $item_num, $row_num, $otherSerial = false)
    {
        return false;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::showItemTitle()
     */
    public function showItemTitle($output_type, $header_num)
    {
        return false;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::showDocTitle()
     */
    public function showDocTitle($output_type, $header_num)
    {
        return false;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::showDocItem()
     */
    public function showDocItem($output_type, $item_num, $row_num, $doc = null)
    {
        return Search::showEndLine($output_type);
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
        if (!$compSerial) {
            // by default
            $info["name"]            = PluginManufacturersimportsConfig::DELL;
            $info['supplier_url']    = "https://www.dell.com/support/home/product-support/servicetag/";
            $info['token_url']       = "https://apigtwb2c.us.dell.com/auth/oauth/v2/token";
            $info['warranty_url']    = "https://apigtwb2c.us.dell.com/PROD/sbil/eapi/v5/asset-entitlements?servicetags=";
            $info["supplier_key"]    = "123456789";
            $info["supplier_secret"] = "987654321";
            return $info;
        }

        $info["url"] = $supplierUrl . "$compSerial";
        return $info;
    }

    /**
     * @return bool
     */
    public function getSearchField()
    {
        return false;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getBuyDate()
     */
    public function getBuyDate($contents)
    {
        $info = json_decode($contents, true);
        // v5
        if (isset($info[0]['shipDate'])) {
            $date = new \DateTime($info[0]['shipDate']);
            return $date->format('c');
        }

        return false;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getStartDate()
     */
    public function getStartDate($contents)
    {
        $info = json_decode($contents, true);
        // v5
        $max_date = false;
        if (isset($info[0]['entitlements'])) {
            foreach ($info[0]['entitlements'] as $d) {
                // ProSupport / ProSupport Plus services
                if (str_contains($d['serviceLevelDescription'], 'ProSupport')) {
                    $date = new \DateTime($d['startDate']);
                    if ($max_date == false || $date > $max_date) {
                        $max_date = $date;
                    }
                }
            }

            if ($max_date) {
                return $max_date->format('c');
            }
        }

        return false;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getExpirationDate()
     */
    public function getExpirationDate($contents)
    {
        $info = json_decode($contents, true);
        // v5
        // when several dates are available, will take the last one
        $max_date = false;
        if (isset($info[0]['entitlements'])) {
            foreach ($info[0]['entitlements'] as $d) {
                // ProSupport / ProSupport Plus services
                if (str_contains($d['serviceLevelDescription'], 'ProSupport')) {
                    $date = new \DateTime($d['endDate']);
                    if ($max_date == false || $date > $max_date) {
                        $max_date = $date;
                    }
                }
            }

            if ($max_date) {
                return $max_date->format('c');
            }
        }


        return false;
    }

    /**
     * @see PluginManufacturersimportsManufacturer::getWarrantyInfo()
     */
    public function getWarrantyInfo($contents)
    {
        $info = json_decode($contents, true);

        // v5
        // when several warranties are available, will take the last one
        $max_date = false;
        $i        = false;
        if (isset($info[0]['entitlements'])) {
            foreach ($info[0]['entitlements'] as $k => $d) {
                // ProSupport / ProSupport Plus services
                if (str_contains($d['serviceLevelDescription'], 'ProSupport')) {
                    $date = new \DateTime($d['endDate']);
                    if ($max_date == false || $date > $max_date) {
                        $max_date = $date;
                        $i        = $k;
                    }
                }
            }
        }

        if ($max_date && $i) {
            return $info[0]['entitlements'][$i]['serviceLevelDescription'];
        }

        return false;
    }

    /**
     * Summary of getToken
     *
     * @param  $config
     *
     * @return mixed
     */
    public static function getToken($config)
    {
        $token = false;
        // must manage token
        $options  = ["url"          => $config->fields["token_url"],
                     "download"     => false,
                     "file"         => false,
                     "post"         => ['client_id'     => $config->fields["supplier_key"],
                                        'client_secret' => $config->fields["supplier_secret"],
                                        'grant_type'    => 'client_credentials'],
                     "suppliername" => $config->fields["name"]];
        $contents = PluginManufacturersimportsPostImport::cURLData($options);
        // must extract from $contents the token bearer
        $response = json_decode($contents, true);
        if (isset($response['access_token'])) {
            $token = $response['access_token'];
        }
        return $token;
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
        return ["url" => $config->fields['warranty_url'] . "$compSerial"];
    }
}
