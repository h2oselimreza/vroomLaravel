<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\Web\WebsiteModule;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.web.website.index");
    }

     public function getTableData(Request $request){
        if ($request->ajax()) {

            $data = WebsiteModule::select([
                'id',
                'module_code',
                'web_module_name',
            ]);

            return DataTables::of($data)
            ->addIndexColumn()

            // Action buttons
            ->addColumn('action', function ($data) {
                $viewUrl   = route('admin.website.module.show', $data->id);
                return '
                    <a href="'.$viewUrl.'" 
                    class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                        <span class="ui-button-text">&nbsp;View</span>
                    </a>
                ';
            })

            ->rawColumns(['action', 'image']) // Important: allow HTML
            ->make(true);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
