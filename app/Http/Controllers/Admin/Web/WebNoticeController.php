<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\NoticeRequest;
use App\Models\Web\Notices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestStatus\Notice;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class WebNoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        return view("admin.web.notice.index");
    }

    public function getTableData(Request $request){
        if ($request->ajax()) {

            $data = Notices::select([
                'id',
                'heading',
                DB::raw('NULL as body'),
                'is_active',
                'publish_date',
            ]);

            return datatables::of($data)
            ->addIndexColumn()
            ->addColumn('is_active', function ($block) {
                    return $block->is_active
                        ? 'Active'
                        : 'Inactive';
                })
            ->addColumn('publish_date', function ($row) {
                return $row->publish_date ? $row->publish_date->format('Y-m-d') : '';
            })
            // Show image
            ->addColumn('image', function ($data) {
                if ($data->image) {
                    $url = asset('images/notices/' . $data->image);
                    return '<img src="' . $url . '" alt="Image" width="150" height="100">';
                }
                return 'No Image';
            })

            // Action buttons
            ->addColumn('action', function ($data) {
                $viewUrl   = route('admin.notices.module.show', $data->id);
                $editUrl   = route('admin.notices.module.edit', $data->id);
                $deleteUrl = route('admin.notices.module.destroy', $data->id);
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

            ->rawColumns(['action', 'is_active','body']) // Important: allow HTML
            ->make(true);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.web.notice.create-update");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoticeRequest $request)
    {
        Notices::create($request->validated());;
        return redirect()
        ->route('admin.notices.module.index')
        ->with('success', 'Notices created successfully.');
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
        $data = Notices::findOrFail($id);
        return view("admin.web.notice.create-update",compact("data"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoticeRequest $request, Notices $notice)
    {
        $notice->update($request->validated());

        return redirect()
            ->route('admin.notices.module.index')
            ->with('success', 'Notices updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notices $notice)
    {
        $notice->delete();
        return redirect()
            ->route('admin.notices.module.index')
            ->with('success', 'Notices deleted successfully.');
    }
}
