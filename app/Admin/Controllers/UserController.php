<?php

namespace App\Admin\Controllers;

use App\AdminRole;
use App\AdminUser;
use App\User;

class UserController extends Controller
{
    //管理人员列表
    public function index()
    {
        dd(234);
        $users = AdminUser::paginate(10);
        return view('admin.user.index', compact('users'));
    }
    //增加管理员
    public function create()
    {
        return view('admin.user.create');
    }

    //增加管理员操作
    public function store()
    {
        // 验证
        $this->validate(request(), [
            'name' => 'required|min:3',
            'password' => 'required',
        ]);

        $name = request('name');
        $password = request('password');
        AdminUser::create(compact('name', 'password'));

        return redirect('/admin/users');
    }

    //用户角色页面
    public function role(AdminUser $user)
    {
        $roles = AdminRole::all();
        $myRoles = $user->roles;
        return view('admin.user.role', compact('user', 'roles', 'myRoles'));
    }

    //储存用户角色
    public function storeRole(AdminUser $user)
    {
        $this->validate(request(), [
            'roles' => 'required|array',
        ]);
        $roles = \App\AdminRole::findMany(request('roles'));
        $myRoles = $user->roles;

        //要增加的
        $addRoles= $roles->diff($myRoles);
        foreach($addRoles as $role){
            $user->assignRole($role);
        }

        //要删除的
        $deleteRoles= $myRoles->diff($roles);
        foreach($deleteRoles as $role){
            $user->deleteRole($role);
        }

        return back();
    }

}
















