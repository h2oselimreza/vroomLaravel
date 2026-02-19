<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleGroup;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserGroupController extends Controller
{
    public function index(){
        //$users = User::select(['id','username','full_name','email','contact_no','user_group','is_active'])->get();
        return view('admin.user_groups.index');
    }

    public function getUserGroups(Request $request)
    {
        if ($request->ajax()) {

            $userGroups = UserGroup::select([
                'id',
                'group_name',
                'is_active'
            ]);

            return DataTables::of($userGroups)

                ->addIndexColumn()

                ->addColumn('is_active', function ($userGroups) {
                    return $userGroups->is_active
                        ? 'Active'
                        : 'Inactive';
                })

                ->addColumn('action', function ($userGroups) {
                    $editUrl   = route('admin.user-groups.edit', $userGroups->id);
                    $activeInactiveUrl   = route('admin.user-groups.status', $userGroups->id);
                    $deleteUrl   = route('admin.user-groups.destroy', $userGroups->id);

                    $statusText = $userGroups->is_active == 1 ? 'Inactive' : 'Active';
                    return '
                        <div class="dropdown">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="' . $editUrl . '" class="d-block ps-3 mb-2">
                                        <span class="ui-button-text">&nbsp;Update</span>
                                    </a>
                                </li>

                                <li role="separator" class="divider"></li>

                                <li>
                                    <form action="' . $activeInactiveUrl . '" method="POST" style="display:inline;">
                                        ' . csrf_field() . '
                                        <input type="hidden" name="_method" value="PATCH">
                                        <button type="submit" class="dropdown-item ps-3">
                                            &nbsp;' . $statusText . '
                                        </button>
                                    </form>
                                </li>

                                <li role="separator" class="divider"></li>

                                <li>
                                    <form action="' . $deleteUrl . '" method="POST"
                                        onsubmit="return confirm(\'Are you sure you want to delete this record?\')">
                                        ' . csrf_field() . '
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="dropdown-item text-danger">
                                            Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function create(){
        $modules = Module::orderBy('module_order', 'asc')->get();
        $moduleGroups = ModuleGroup::orderBy('module_group_order', 'asc')->get();
        return view('admin.user_groups.create_update',compact('modules','moduleGroups'));
    }

    public function edit($id)
    {
        $userGroup = UserGroup::findOrFail($id);
        $modules = Module::orderBy('module_order', 'asc')->get();
        $moduleGroups = ModuleGroup::orderBy('module_group_order', 'asc')->get();
        return view('admin.user_groups.create_update', compact(
            'modules',
            'moduleGroups',
            'userGroup'
        ));
    }

    public function storeOrUpdate(Request $request, $id = null)
    {
         $request->validate([
            'module_group_name' => 'required|string|max:255',
            'moduleList'        => 'required|array',
        ]);

        $moduleGroup = $id 
            ? UserGroup::findOrFail($id)
            : new UserGroup();

        $moduleGroup->group_name = $request->module_group_name;
        $moduleGroup->modules = implode(',', $request->moduleList);
        $moduleGroup->is_active = 1;

        $moduleGroup->save();

        return redirect()->route('admin.user-groups.index')
            ->with('success', $id ? 'User group updated Successfully' : 'User group created Successfully');
        }

        public function updateStatus($id)
        {
            $userGroup = UserGroup::findOrFail($id);
            $userGroup->is_active = $userGroup->is_active == 1 ? 0 : 1;
            $userGroup->save();
            return redirect()->back()->with('success', 'Status Updated Successfully');
        }

        public function destroy($id)
        {
            $userGroup = UserGroup::findOrFail($id);
            $userGroup->delete();
            return redirect()->back()->with('success', 'User group deleted Successfully');
        }
}
