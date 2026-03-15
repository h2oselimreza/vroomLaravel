<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\FooterSliderRequest;
use App\Http\Requests\Web\SliderRequest;
use App\Models\Web\Slider;
use App\Models\WebFooterImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class FooterSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.web.footer-slider.index");
    }

     public function getTableData(Request $request){
        if ($request->ajax()) {

            $data = WebFooterImage::select([
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
                    $url = asset('assets/images/websiteImages/' . $data->image);
                    return '<img src="' . $url . '" alt="Slider Image" width="200" height="150">';
                }
                return 'No Image';
            })

            // Action buttons
            ->addColumn('action', function ($data) {
                $editUrl   = route('admin.footer-slider.module.edit', $data->id);
                $deleteUrl = route('admin.footer-slider.module.destroy', $data->id);
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
         return view('admin.web.footer-slider.create-update');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FooterSliderRequest $request)
    {
        $image = $request->file('image');
        $filename = Str::random(10) . '-' . $image->getClientOriginalName();
        $image->move(public_path('assets/images/websiteImages/'), $filename);

        WebFooterImage::create([
        'image' => $filename,
        'image_order' => $request->image_order,
        ]);

        return redirect()->route('admin.footer-slider.module.index')->with('success', 'Footer slider created successfully.');

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
    public function edit(WebFooterImage $footer_slider)
    {
        $data = $footer_slider;
        return view('admin.web.footer-slider.create-update', compact('data'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(FooterSliderRequest $request, WebFooterImage $footer_slider)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::random(10) . '-' . $image->getClientOriginalName();
            $image->move(public_path('assets/images/websiteImages/'), $filename);

            if ($footer_slider->image && file_exists(public_path('assets/images/websiteImages/' . $footer_slider->image))) {
                unlink(public_path('assets/images/websiteImages/' . $footer_slider->image));
            }

            $footer_slider->image = $filename;
        }

        $footer_slider->image_order = $request->image_order;

        $footer_slider->save();

        return redirect()->route('admin.footer-slider.module.index')
            ->with('success', 'Footer slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebFooterImage $footer_slider)
    {
        // Delete the image file if it exists
        if ($footer_slider->image && file_exists(public_path('assets/images/websiteImages/' . $footer_slider->image))) {
            unlink(public_path('assets/images/websiteImages/' . $footer_slider->image));
        }

        $footer_slider->delete();

        return redirect()->route('admin.footer-slider.module.index')
            ->with('success', 'Footer slider updated successfully.');
    }
}
