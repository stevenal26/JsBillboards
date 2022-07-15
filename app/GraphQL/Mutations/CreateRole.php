<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Models\RoleMenu;
use App\Models\Role;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class CreateRole
{

    public function __construct()
    {
        
    }

    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        DB::beginTransaction();
        try {
            // check if the role exists
            $role = Role::where('name', $args['role_name'])->first();
            if ( $role != null ) {
                return [
                    'status' => false,
                    'message' => "The '{$args['role_name']}' Role already exists"
                ];
            }

            // create the new role
            $newRole = new Role();
            $newRole->name = $args['role_name'];
            $newRole->save();
            
            // create the permissions
            foreach ( $args['permissions'] as $permission ) {
                // check if the menu is valid
                $menu = Menu::where('id', $permission['menu_id'])->first();
                if ( $menu == null ) {
                    return [
                        'status' => false,
                        'message' => "Invalid menu exists"
                    ];
                }

                // create the new role_menu
                $newRoleMenu = new RoleMenu();
                $newRoleMenu->role_id = $newRole->id;
                $newRoleMenu->menu_id = $permission['menu_id'];
                $newRoleMenu->is_view = $permission['is_view'];
                $newRoleMenu->is_create = $permission['is_create'];
                $newRoleMenu->is_modify = $permission['is_modify'];
                $newRoleMenu->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return [
                'status' => false,
                'message' => __('create_role_permission.error')
            ];
        }
        
        return [
            'status' => true,
            'message' => __('create_role_permission.created')
        ];
    }
}