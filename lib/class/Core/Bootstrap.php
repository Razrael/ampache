<?php
/* vim:set softtabstop=4 shiftwidth=4 expandtab: */
/**
 *
 * LICENSE: GNU General Public License, version 2 (GPLv2)
 * Copyright 2001 - 2016 Ampache.org
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License v2
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */

namespace Lib\Core;

use AmpConfig;
use Core;
use Lib\Core\Controller\AbstractController;
use Lib\Singleton;
use Preference;
use Session;

class Bootstrap
{
    use Singleton;


    private static $nonClassIncludes = [
        '/lib/general.lib.php',
        '/lib/preferences.php',
        '/lib/debug.lib.php',
        '/lib/log.lib.php',
        '/lib/ui.lib.php',
        '/lib/i18n.php',
        '/lib/batch.lib.php',
        '/lib/themes.php',
        '/modules/horde/Browser.php',
    ];

    public function run()
    {
        error_reporting(E_ALL);
        $this->defineConstants();
        $this->includeOldAutoloader();
        $this->includeNonClassFiles();
        $this->safetyChecks();
        $this->includeConfig();
        $this->startSesstion();
        $this->runController();
    }

    private function safetyChecks()
    {
        if (version_compare(phpversion(), '7.0.0', '<')) {
            throw new Exception('Ampache requires PHP version >= 7.0');
        }
    }

    private function includeNonClassFiles()
    {
        foreach(self::$nonClassIncludes as $include) {
            require AMPACHE_PATH . $include;
        }
    }

    private function defineConstants()
    {
        define('AMPACHE_PATH', $this->getAmpachePath());
        AmpConfig::set('prefix', AMPACHE_PATH);
        define('INIT_LOADED', true);
    }

    private function includeConfig()
    {
        $configfile = AMPACHE_PATH . '/config/ampache.cfg.php';
        if(is_readable($configfile)) {
            $results = parse_ini_file($configfile);
            $results = Preference::fix_preferences($results);
            AmpConfig::set_by_array($results, true);
        }
        else {
            throw new Exception($configfile . ' is not readable');
        }
    }

    private function getAmpachePath()
    {
        return dirname($_SERVER['SCRIPT_FILENAME']);
    }

    private function includeOldAutoloader()
    {
        spl_autoload_register(array(Core::class, 'autoload'), true, true);
    }

    private function startSesstion()
    {
        Session::_auto_init();
        if (!Core::is_session_started()) {
            session_start();
        }
    }

    private function runController($controllerClass = Controller\Browse::class, $action = 'Show')
    {
        $controller = $_REQUEST['controller'] ?? $controllerClass;
        $action = $_REQUEST['action'] ?? $action;
        if(is_subclass_of($controller, AbstractController::class)) {
            $controller = new $controller();
        }
        else {
            $controller = new $controllerClass();
        }
        echo $controller->renderView($action);
    }

}