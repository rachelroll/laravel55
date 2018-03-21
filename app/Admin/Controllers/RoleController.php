<?php

namespace App\Admin\Controllers;

use App\AdminPermission;
use App\AdminRole;
use App\AdminUser;
class RoleController extends Controller
{
    //角色列表
    public function index()
    {
        $roles = AdminRole::paginate(10);
        return view('admin.role.index', compact('roles'));
    }

    //创建角色页面
    public function create()
    {
        return view('admin.role.create');
    }

    //创建角色行为
    public function store()
    {
        // 验证
        $this->validate(request(), [
            'name' => 'required|min:3',
            'description' => 'required|max:20',
        ]);
        //逻辑
        $name = request('name');
        $description = request('description');
        AdminRole::create(compact('name', 'description'));
        //渲染
        return redirect('/admin/roles');
    }

    //角色权限关系页面
    public function permission(AdminRole $role)
    {
        $permissions = AdminPermission::all();
        $myPermissions = $role->permissions;
        return view('admin.role.permission', compact('permissions', 'myPermissions', 'role'));
    }

    //给角色增加权限的行为
    public function storePermission(AdminRole $role)
    {
        // 验证
        $this->validate(request(), [
            'permissions' => 'required|array',
        ]);
        //逻辑
        $permissions = AdminPermission::find(request('permissions'));
        $myPermissions = $role->permissions;

        $addPermissions = $permissions->diff($myPermissions);
        foreach($addPermissions as $permission) {
            $role->grantPermission($permission);
        }

        $deletePermisions = $myPermissions->diff($permissions);
        foreach($deletePermisions as $permission) {
            $role->deletePermission($permission);
        }

        //渲染
        return back();
    }

}
