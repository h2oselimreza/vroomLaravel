<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\NewsRequest;
use App\Models\Web\WebNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.web.news.index");
    }

    
    public function getNewsData(Request $request){
        if ($request->ajax()) {

            $webnews = WebNews::select([
                'id',
                'heading',
                DB::raw('NULL as body'),
                'is_active',
                'publish_date',
            ]);

            return DataTables::of($webnews)

                ->addIndexColumn()

                ->addColumn('is_active', function ($webnews) {
                    return $webnews->is_active
                        ? 'Active'
                        : 'Inactive';
                })

                ->addColumn('action', content: function ($webnews) {
                    $viewUrl   = route('admin.news.module.show', $webnews->id);
                    $editUrl   = route('admin.news.module.edit', $webnews->id);
                    $deleteUrl = route('admin.news.module.destroy', $webnews->id);
                    return '
                        <a href="'.$viewUrl.'" 
                        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary action_button_modify">
                            <span class="ui-button-text">&nbsp;View</span>
                        </a>

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

                ->rawColumns(['body','action'])
                ->make(true);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.web.news.create-update');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewsRequest $request)
    {
        WebNews::create($request->validated());
        return redirect()
        ->route('admin.news.module.index')
        ->with('success', 'Latest news created successfully.');
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
    public function edit(WebNews $news)
    {
        $data = $news;
        return view('admin.web.news.create-update', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NewsRequest $request, WebNews $news)
    {
        $news->update($request->validated());

        return redirect()
            ->route('admin.news.module.index')
            ->with('success', 'News updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebNews $news)
    {
        $news->delete();
        return redirect()
            ->route('admin.news.module.index')
            ->with('success', 'News deleted successfully.');
    }
}
