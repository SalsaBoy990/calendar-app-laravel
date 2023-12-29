<?php

namespace App\Http\Controllers\Admin;

use App\Interface\Services\RolePermissionServiceInterface;
use App\Support\InteractsWithBanner;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class RolePermissionController extends Controller
{
    use InteractsWithBanner;


    /**
     * @var RolePermissionServiceInterface
     */
    private RolePermissionServiceInterface $rolePermissionService;


    /**
     * @param  RolePermissionServiceInterface  $rolePermissionService
     */
    public function __construct(RolePermissionServiceInterface $rolePermissionService)
    {
        $this->rolePermissionService = $rolePermissionService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $permissions = $this->rolePermissionService->getPermissionsWithRoles();
        $roles = $this->rolePermissionService->getRolesWithPermissions();

        return view('admin.pages.role_permission.manage')->with([
            'permissions' => $permissions,
            'roles' => $roles,
        ]);
    }
}
