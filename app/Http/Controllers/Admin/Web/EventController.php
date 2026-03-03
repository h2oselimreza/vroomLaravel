<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\EventRequest;
use App\Models\Web\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.web.events.index");
    }

    public function getTableData(Request $request){
        if ($request->ajax()) {

            $data = Event::select([
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
            ->addColumn('is_active', function ($block) {
                    return $block->is_active
                        ? 'Active'
                        : 'Inactive';
                })

            // Show image
            ->addColumn('image', function ($data) {
                if ($data->image) {
                    $url = asset('images/events/' . $data->image);
                    return '<img src="' . $url . '" alt="Image" style="height:50px">';
                }
                return 'No Image';
            })

            // Action buttons
            ->addColumn('action', function ($data) {
                $viewUrl   = route('admin.events.module.show', $data->id);
                $editUrl   = route('admin.events.module.edit', $data->id);
                $deleteUrl = route('admin.events.module.destroy', $data->id);
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

            ->rawColumns(['action', 'image']) // Important: allow HTML
            ->make(true);

        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.web.events.create-update");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::random(10) . '-' . $image->getClientOriginalName();
            $image->move(public_path('images/events'), $filename);

            $data['image'] = $filename;
        }
        Event::create($data);;

        return redirect()
        ->route('admin.events.module.index')
        ->with('success', 'Events created successfully.');
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
        $data = Event::findOrFail($id);
        return view('admin.web.events.create-update',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, string $id)
    {
        $achievement = Event::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            if ($achievement->image && file_exists(public_path('images/events/' . $achievement->image))) {
                unlink(public_path('images/events/' . $achievement->image));
            }

            $image = $request->file('image');
            $filename = Str::random(10) . '-' . $image->getClientOriginalName();
            $image->move(public_path('images/events'), $filename);

            $data['image'] = $filename;
        }

        $achievement->update($data);

        return redirect()
            ->route('admin.events.module.index')
            ->with('success', 'Events updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()
            ->route('admin.events.module.index')
            ->with('success', 'Event deleted successfully.');
    }
}
