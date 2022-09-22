<?php

namespace app\shop;

use yi\Menu;

class Plugin
{
    public static function install() 
    {
        Menu::install('shop', 'admin', 'admin');
        Menu::install('shop', 'user', 'index,api');
    }

    public static function uninstall()
    {
        Menu::uninstall('shop', 'admin');
        Menu::uninstall('shop', 'user');
    }    

    public static function enable()
    {
        Menu::enable('shop', 'admin');
        Menu::enable('shop', 'user');
    }

    public static function disable()
    {
        Menu::disable('shop', 'admin');
        Menu::disable('shop', 'user');
    }
}