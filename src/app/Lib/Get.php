<?php
namespace App\Lib;

use Illuminate\Support\Facades\Request;
use App\Providers\RouteServiceProvider;

class Get
{
    public static function home()
    {
        $prefix = Request::route()->getPrefix();
        if ($prefix === 'admin') return RouteServiceProvider::ADMIN_HOME;
        elseif ($prefix === 'owner') return RouteServiceProvider::OWNER_HOME;
        elseif ($prefix === '/') return RouteServiceProvider::HOME;
    }
    public static function routePrefix()
    {
        $prefix = Request::route()->getPrefix();
        return ($prefix === '/')? 'user' : $prefix;
    }


}

