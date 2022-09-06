<?php

namespace App\Services;
use Illuminate\Support\Facades\Gate;

class PermissionGateAndPolicyAccess {
    public function setGateAndPolicyAccess(){
        $this->defineBlogPolicy();
        $this->defineMenuPolicy();
    }

    public function defineBlogPolicy(){
        Gate::define('list_blog', 'App\Policies\BlogPolicy@view');
        Gate::define('add_blog', 'App\Policies\BlogPolicy@create');
        Gate::define('edit_blog', 'App\Policies\BlogPolicy@update');
        Gate::define('delete_blog', 'App\Policies\BlogPolicy@delete');
        Gate::define('accept_blog', 'App\Policies\BlogPolicy@accept');
    }

    public function defineMenuPolicy(){
        Gate::define('list_menu', 'App\Policies\MenuPolicy@view');
        Gate::define('add_menu', 'App\Policies\MenuPolicy@create');
        Gate::define('edit_menu', 'App\Policies\MenuPolicy@update');
        Gate::define('delete_menu', 'App\Policies\MenuPolicy@delete');
    }

    public function definePermissionPolicy(){
        Gate::define('list_permission', 'App\Policies\PermissionPolicy@view');
        Gate::define('add_permission', 'App\Policies\PermissionPolicy@create');
        Gate::define('edit_permission', 'App\Policies\PermissionPolicy@update');
        Gate::define('delete_permission', 'App\Policies\PermissionPolicy@delete');
        Gate::define('setup_permission', 'App\Policies\PermissionPolicy@setup');
    }
}