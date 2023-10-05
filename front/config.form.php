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

include('../../../inc/includes.php');

if (!isset($_GET["id"])) {
    $_GET["id"] = 0;
}
if (!isset($_GET["preconfig"])) {
    $_GET["preconfig"] = -1;
}

$config = new PluginManufacturersimportsConfig();
$model = new PluginManufacturersimportsModel();

if (isset($_POST["add"])) {
    Session::checkRight("plugin_manufacturersimports", CREATE);
    $config->add($_POST);
    Html::back();

} else if (isset($_POST["update"])) {

    Session::checkRight("plugin_manufacturersimports", UPDATE);
    $config->update($_POST);
    Html::back();

} else if (isset($_POST["delete"])) {

    Session::checkRight("plugin_manufacturersimports", PURGE);
    $config->delete($_POST, true);
    Html::redirect("./config.form.php");

} else if (isset($_POST["update_model"])) {
    Session::checkRight("plugin_manufacturersimports", UPDATE);
    $model->addModel($_POST);
    Html::back();

} else if (isset($_POST["delete_model"])) {
    Session::checkRight("plugin_manufacturersimports", UPDATE);
    $model->delete($_POST);
    Html::back();

} else if (isset($_POST["retrieve_warranty"])) {
    Session::checkRight("plugin_manufacturersimports", UPDATE);

    PluginManufacturersimportsConfig::retrieveOneWarranty($_POST["itemtype"], $_POST["items_id"]);

    Html::back();

} else {

    Html::header(__('Setup'), '', "tools", "pluginmanufacturersimportsmenu", "config");

    $config->checkGlobal(READ);
    $config->display($_GET);
    Html::footer();
}
