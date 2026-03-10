<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ModuleDescriptionRequest;
use App\Models\Web\ModuleDetail;
use App\Models\Web\Slider;
use App\Models\Web\WebsiteModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class WebModuleDescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.web.web-module-description.index");
    }

    public function getTableData(Request $request){
        if ($request->ajax()) {

            $data = ModuleDetail::select([
                'id',
                'module_code',
                'heading',
                'short_description',
                'image',
                'description',
            ]);

            return DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('short_description', function ($row) {
                return Str::words($row->short_description, 15, '...');
            })
            ->addColumn('description', function ($row) {
                return Str::words($row->short_description, 15, '...');
            })

            // Show image
            ->addColumn('image', function ($data) {
                if ($data->image) {
                    $url = asset('images/module-description/' . $data->image);
                    return '<img src="' . $url . '" alt="module-description Image" width="200" height="150">';
                }
                return 'No Image';
            })

            // Action buttons
            ->addColumn('action', function ($data) {
                $viewUrl   = route('admin.module-description.module.show', $data->id);
                $editUrl   = route('admin.module-description.module.edit', $data->id);
                $deleteUrl = route('admin.module-description.module.destroy', $data->id);
                return '
                    <a href="'.$editUrl.'" 
                    class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                    <span class="ui-button-text">&nbsp;Edit</span>
                    </a>

                    <a onclick="deleteRecord(\''.$deleteUrl.'\')" 
                    href="javascript:void(0)" 
                    class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                    <span class="ui-button-text">&nbsp;Delete</span>
                    </a>
                ';
            })

            ->rawColumns(['action', 'image','description']) // Important: allow HTML
            ->make(true);

        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $webSiteModule = WebsiteModule::get();
        return view('admin.web.web-module-description.create-update',compact('webSiteModule'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ModuleDescriptionRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::random(10) . '-' . $image->getClientOriginalName();
            $image->move(public_path('images/module-description'), $filename);

            $data['image'] = $filename;
        }

        ModuleDetail::create($data);;
        return redirect()
        ->route('admin.module-description.module.index')
        ->with('success', 'Module description created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = ModuleDetail::findOrFail($id);
        $webSiteModule = WebsiteModule::get();
        return view('admin.web.web-module-description.create-update', compact('data','webSiteModule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ModuleDescriptionRequest $request, $id)
    {
        $moduleDetail = ModuleDetail::findOrFail($id);

        $validatedData = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {

            // Optional: delete old image
            if ($moduleDetail->image && file_exists(public_path('images/module-description/' . $moduleDetail->image))) {
                unlink(public_path('images/module-description/' . $moduleDetail->image));
            }

            $image = $request->file('image');
            $filename = Str::random(10) . '-' . $image->getClientOriginalName();
            $image->move(public_path('images/module-description'), $filename);

            $validatedData['image'] = $filename;
        }

        $moduleDetail->update($validatedData);

        return redirect()
            ->route('admin.module-description.module.index')
            ->with('success', 'Module description updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = ModuleDetail::findOrFail($id);
        $data->delete();
        return redirect()
            ->route('admin.module-description.module.index')
            ->with('success', 'Module description deleted successfully.');
    }
}
