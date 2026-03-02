<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SliderRequest;
use App\Models\Web\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class WebSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.web.slider.index");
    }

     public function getTableData(Request $request){
        if ($request->ajax()) {

            $data = Slider::select([
                'id',
                'image',
                'image_order',
            ]);

            return DataTables::of($data)
            ->addIndexColumn()

            // Show Active/Inactive
            ->addColumn('is_active', function ($data) {
                return $data->is_active ? 'Active' : 'Inactive';
            })

            // Show image
            ->addColumn('image', function ($data) {
                if ($data->image) {
                    $url = asset('images/slider/' . $data->image);
                    return '<img src="' . $url . '" alt="Slider Image" width="200" height="150">';
                }
                return 'No Image';
            })

            // Action buttons
            ->addColumn('action', function ($data) {
                $viewUrl   = route('admin.slider.module.show', $data->id);
                $editUrl   = route('admin.slider.module.edit', $data->id);
                $deleteUrl = route('admin.slider.module.destroy', $data->id);
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
         return view('admin.web.slider.create-update');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SliderRequest $request)
    {
        $image = $request->file('image');
        $filename = Str::random(10) . '-' . $image->getClientOriginalName();
        $image->move(public_path('images/slider'), $filename);

        Slider::create([
        'image' => $filename,
        'image_order' => $request->image_order,
        ]);

        return redirect()->route('admin.slider.module.index')->with('success', 'Slider created successfully.');

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
    public function edit(Slider $slider)
    {
        $data = $slider;
        return view('admin.web.slider.create-update', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SliderRequest $request, Slider $slider)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::random(10) . '-' . $image->getClientOriginalName();
            $image->move(public_path('images/slider'), $filename);

            if ($slider->image && file_exists(public_path('images/slider/' . $slider->image))) {
                unlink(public_path('images/slider/' . $slider->image));
            }

            $slider->image = $filename;
        }

        $slider->image_order = $request->image_order;

        $slider->save();

        return redirect()->route('admin.slider.module.index')
            ->with('success', 'Slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        // Delete the image file if it exists
        if ($slider->image && file_exists(public_path('images/slider/' . $slider->image))) {
            unlink(public_path('images/slider/' . $slider->image));
        }

        $slider->delete();

        return redirect()->route('admin.slider.module.index')
            ->with('success', 'Slider updated successfully.');
    }
}
