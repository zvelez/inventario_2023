<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RoleController extends Controller {
  
  public static function checkPermission($ROLES_CODE) {
    $account = Auth::user();
    if(empty($account)) {
      return ;
    }
    if($account->status != 1) {
      $auth_ctrl = new AuthenticatedSessionController();
      $auth_ctrl->destroy(request());
    }
    $role = Role::find($account->role_id);
    //dd($role->code, $ROLES_CODE);
    return in_array($role->code, $ROLES_CODE);
  }
  
  public static function getMenu() {
    $menu = [];
    if(RoleController::checkPermission(['ADMSYS', 'ADMIN'])) {
      $menu[] = [
        'url' => route('users'),
        'active' => strpos(Route::currentRouteName(), 'users'),
        'label' => 'Usuarios',
      ];
    }
    if(RoleController::checkPermission(['ADMSYS', 'PROD', 'DESP', 'CALID'])) {
      $menu[] = [
        'url' => route('clients'),
        'active' => strpos(Route::currentRouteName(), 'clients'),
        'label' => 'Clientes',
      ];
    }
    if(RoleController::checkPermission(['ADMSYS', 'PROD', 'CALID'])) {
      $menu[] = [
        'url' => route('manufacturers'),
        'active' => strpos(Route::currentRouteName(), 'manufacturers'),
        'label' => 'Talleres',
      ];
    }
    if(RoleController::checkPermission(['ADMSYS', 'PROD'])) {
      $menu[] = [
        'url' => route('suppliers'),
        'active' => strpos(Route::currentRouteName(), 'suppliers'),
        'label' => 'Proveedores',
      ];
    }
    if(RoleController::checkPermission(['ADMSYS', 'ALMAC'])) {
      $menu[] = [
        'url' => route('inventory'),
        'active' => strpos(Route::currentRouteName(), 'inventory'),
        'label' => 'Inventario',
      ];
    }
    if(RoleController::checkPermission(['ADMSYS', 'ALMAC', 'PROD', 'CALID'])) {
      $menu[] = [
        'url' => route('orders'),
        'active' => strpos(Route::currentRouteName(), 'orders'),
        'label' => 'Pedidos',
      ];
    }
    if(RoleController::checkPermission(['ADMSYS', 'PROD', 'DESP', 'CALID'])) {
      $menu[] = [
        'url' => route('work-progress'),
        'active' => strpos(Route::currentRouteName(), 'work-progress'),
        'label' => 'Trabajos en curso',
      ];
    }
    $menu[] = [
      'url' => route('deliveries'),
      'active' => strpos(Route::currentRouteName(), 'deliveries'),
      'label' => 'Entregas',
    ];
    $menu[] = [
      'url' => route('reports'),
      'active' => strpos(Route::currentRouteName(), 'reports'),
      'label' => 'Reportes',
    ];
    //dd($menu);
    return $menu;
  }
}
