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

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

$log = new PluginManufacturersimportsLog();
$config = new PluginManufacturersimportsConfig();

Html::header(_n('Suppliers import', 'Suppliers imports', 2, 'manufacturersimports'),
    $_SERVER['PHP_SELF'], "tools", "pluginmanufacturersimportsmenu");

$config->checkGlobal(UPDATE);

if (isset($_POST["action"])
    && isset($_POST["id"])
    && isset($_POST["item"])
    && count($_POST["item"])) {
    switch ($_POST["action"]) {
        case "import":
            PluginManufacturersimportsPostImport::massiveimport($_POST);
            break;

        case "reinit_once":
            foreach ($_POST["item"] as $key => $val) {
                if ($val == 1) {
                    $log->reinitializeImport($_POST["itemtype"], $key);
                }
            }
            Session::addMessageAfterRedirect(__('Operation successful'));
            Html::redirect($_SERVER['HTTP_REFERER'] . "?itemtype=" . $_POST["itemtype"] .
                "&manufacturers_id=" . $_POST["manufacturers_id"] .
                "&start=" . $_POST["start"] .
                "&imported=" . $_POST["imported"]);
            break;
    }
} else {
   echo "<div class='alert alert-important alert-warning d-flex'>";
    echo "<b>" . __('No selected element or badly defined operation') . "</b></div>";
}

Html::footer();
