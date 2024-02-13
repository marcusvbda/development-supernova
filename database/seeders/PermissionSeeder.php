<?php

namespace Database\Seeders;

use App\Http\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    const CREATE = ['create', 'cadastrar'];
    const EDIT = ['edit', 'editar'];
    const DELETE = ['delete', 'excluir'];
    const COMPLETE_PERMISSIONS = [self::CREATE, self::EDIT, self::DELETE];

    public function makePermissions($type, $value, $permissions = self::COMPLETE_PERMISSIONS)
    {
        $createdPermissions = [];
        DB::beginTransaction();
        foreach ($permissions as $permission) {
            $valueIndex = $permission[0];
            $valueTranslate = $permission[1];
            $permissionName = ucfirst(strtolower("$valueTranslate $type"));
            $permissionKey = "$valueIndex-$value";

            $createdPermissions[] = Permission::updateOrCreate(
                ['type' => $type, 'key' => $permissionKey],
                ['name' => $permissionName, 'type' => $type, 'key' => $permissionKey]
            );
        }
        DB::commit();
        return $createdPermissions;
    }


    public function deletePermissionType($type)
    {
        $createdPermissions = [];
        DB::beginTransaction();
        $ids = Permission::where('type', $type)->pluck('id')->toArray();
        DB::table('access_group_permissions')->whereIn('permission_id', $ids)->delete();
        Permission::whereIn('id', $ids)->delete();
        DB::commit();
        return $createdPermissions;
    }
}
