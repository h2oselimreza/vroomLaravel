<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\AchievementsRequest;
use App\Models\Web\WebAchievement;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class WebAchievementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.web.achievements.index");
    }

    public function getTableData(Request $request){
        if ($request->ajax()) {

            $data = WebAchievement::select([
                'id',
                'heading',
                'short_description',
                'details',
                'is_active',
                'image',
                'date',
            ]);

            return DataTables::of($data)
            ->addIndexColumn()

            // Show image
            ->addColumn('image', function ($data) {
                if ($data->image) {
                    $url = asset('images/achievements/' . $data->image);
                    return '<img src="' . $url . '" alt="module-description Image" width="150" height="100">';
                }
                return 'No Image';
            })
            ->addColumn('short_description', function ($row) {
                return Str::words($row->short_description, 15, '...');
            })

            
            ->addColumn('details', function ($row) {
                return Str::words($row->details, 15, '...');
            })

            // Action buttons
            ->addColumn('action', function ($data) {
                $viewUrl   = route('admin.achievements.module.show', $data->id);
                $editUrl   = route('admin.achievements.module.edit', $data->id);
                $deleteUrl = route('admin.achievements.module.destroy', $data->id);
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

            ->rawColumns(['action', 'image', 'details']) // Important: allow HTML
            ->make(true);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.web.achievements.create-update');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AchievementsRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::random(10) . '-' . $image->getClientOriginalName();
            $image->move(public_path('images/achievements'), $filename);

            $data['image'] = $filename;
        }
        WebAchievement::create($data);;

        return redirect()
        ->route('admin.achievements.module.index')
        ->with('success', 'Achievements created successfully.');
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
        $data = WebAchievement::findOrFail($id);
        return view('admin.web.achievements.create-update',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AchievementsRequest $request, $id)
    {
        $achievement = WebAchievement::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            if ($achievement->image && file_exists(public_path('images/achievements/' . $achievement->image))) {
                unlink(public_path('images/achievements/' . $achievement->image));
            }

            $image = $request->file('image');
            $filename = Str::random(10) . '-' . $image->getClientOriginalName();
            $image->move(public_path('images/achievements'), $filename);

            $data['image'] = $filename;
        }

        $achievement->update($data);

        return redirect()
            ->route('admin.achievements.module.index')
            ->with('success', 'Achievement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebAchievement $achievement)
    {
        $achievement->delete();
        return redirect()
            ->route('admin.achievements.module.index')
            ->with('success', 'Achievement deleted successfully.');
    }
}
