<?php

namespace App\Policies;

use App\Models\RegisterCloseout;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegisterCloseoutPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if (!$user->isAdministrator()) {
            if (!$user->hasSelectedUnit()) {
                return false;
            }
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // permission check
        $roleId = $user->role_id;
        $menu = DB::table('menus')->where('slug_name', '=', 'close-register')->first();
        if ( $roleId == null || $menu == null ) {
            return false;
        }
        $roleMenu = DB::table('role_menus')->where('role_id', '=', $roleId)->where('menu_id', '=', $menu->id)->first();
        if ( $roleMenu == null ) {
            return false;
        }
        $permission = $roleMenu->is_view;
        if ( $permission == 1 ) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RegisterCloseout  $registerCloseout
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, RegisterCloseout $registerCloseout)
    {
        // permission check
        $roleId = $user->role_id;
        $menu = DB::table('menus')->where('slug_name', '=', 'close-register')->first();
        if ( $roleId == null || $menu == null ) {
            return false;
        }
        $roleMenu = DB::table('role_menus')->where('role_id', '=', $roleId)->where('menu_id', '=', $menu->id)->first();
        if ( $roleMenu == null ) {
            return false;
        }
        $permission = $roleMenu->is_view;
        if ( $permission == 1 ) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ( !$user->isAdministrator() ) {

            // permission check
            $roleId = $user->role_id;
            $menu = DB::table('menus')->where('slug_name', '=', 'close-register')->first();
            if ( $roleId == null || $menu == null ) {
                return false;
            }
            $roleMenu = DB::table('role_menus')->where('role_id', '=', $roleId)->where('menu_id', '=', $menu->id)->first();
            if ( $roleMenu == null ) {
                return false;
            }
            $permission = $roleMenu->is_create;
            if ( $permission == 1 ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RegisterCloseout  $registerCloseout
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, RegisterCloseout $registerCloseout, array $injectedArgs)
    {   
        // TODO: refactor
        $updateIds = array_column($injectedArgs['items']['update'] ?? [], 'id');

        $deleteIds = $injectedArgs['items']['delete'] ?? [];

        if ($user->activePeriod()?->id === $registerCloseout->period_id &&
            $user->id === $registerCloseout->user_id &&
            !$user->isAdministrator() &&
            $registerCloseout->containItems($updateIds) &&
            $registerCloseout->containItems($deleteIds)) {
            
            // permission check
            $roleId = $user->role_id;
            $menu = DB::table('menus')->where('slug_name', '=', 'close-register')->first();
            if ( $roleId == null || $menu == null ) {
                return false;
            }
            $roleMenu = DB::table('role_menus')->where('role_id', '=', $roleId)->where('menu_id', '=', $menu->id)->first();
            if ( $roleMenu == null ) {
                return false;
            }
            $permission = $roleMenu->is_modify;
            if ( $permission == 1 ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RegisterCloseout  $registerCloseout
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, RegisterCloseout $registerCloseout)
    {
        if ($user->activePeriod()?->id === $registerCloseout->period_id &&
            $user->id === $registerCloseout->user_id &&
            !$user->isAdministrator()) {

            // permission check
            $roleId = $user->role_id;
            $menu = DB::table('menus')->where('slug_name', '=', 'close-register')->first();
            if ( $roleId == null || $menu == null ) {
                return false;
            }
            $roleMenu = DB::table('role_menus')->where('role_id', '=', $roleId)->where('menu_id', '=', $menu->id)->first();
            if ( $roleMenu == null ) {
                return false;
            }
            $permission = $roleMenu->is_modify;
            if ( $permission == 1 ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RegisterCloseout  $registerCloseout
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, RegisterCloseout $registerCloseout)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RegisterCloseout  $registerCloseout
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, RegisterCloseout $registerCloseout)
    {
        //
    }
}
