<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\Web\WebGalleryAlbum;
use App\Models\Web\WebGalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ImageAlbumsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("admin.web.gallay-image.index");
    }

    public function getTableData(Request $request)
    {
        if ($request->ajax()) {

            $data = WebGalleryAlbum::with('images')
                ->select('id', 'album_name', 'album_order');

            return DataTables::of($data)
                ->addIndexColumn()

                // Show images
                ->addColumn('image', function ($row) {

                if ($row->images->count() > 0) {

                    $html = '';

                    // Take only first 3 images
                    $images = $row->images->take(3);

                    foreach ($images as $image) {
                        $url = asset('assets/images/websiteImages/' . $image->image);

                        $html .= '<img src="' . $url . '" 
                                style="
                                    height:50px;;
                                    width: 70px;;
                                    margin:2px;
                                    border-radius:5px;
                                    object-fit:cover;
                                    border:1px solid #ddd;
                                ">';
                    }

                    // If more than 3 images, show +X badge
                    // if ($row->images->count() > 3) {
                    //     $extraCount = $row->images->count() - 3;

                    //     $html .= '<span style="
                    //         display:inline-block;
                    //         height:50px;
                    //         width:50px;
                    //         line-height:50px;
                    //         text-align:center;
                    //         background:#eee;
                    //         border-radius:5px;
                    //         font-weight:bold;">
                    //         +'.$extraCount.'
                    //     </span>';
                    // }

                    return $html;
                }

                return 'No Image';
                })

                // Action buttons
                ->addColumn('action', function ($row) {

                    $editUrl   = route('admin.gallery-image.module.edit', $row->id);
                    $deleteUrl = route('admin.gallery-image.module.destroy', $row->id);

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

                ->rawColumns(['image','action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.web.gallay-image.create-update");
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request) {
        return $this->storeOrUpdate($request); // no $id
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
        $data = WebGalleryAlbum::with('images')->findOrFail($id);
        return view("admin.web.gallay-image.create-update",compact("data"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->storeOrUpdate($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $album = WebGalleryAlbum::findOrFail($id);
        $album->delete();

        return redirect()
            ->route('admin.gallery-image.module.index')
            ->with('success', 'Deleted successfully.');
    }

    private function storeOrUpdate(Request $request, $id = null)
    {
        $request->validate([
            'album_name' => 'required|string|max:255',
            'image.*'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            if ($id) {
                $album = WebGalleryAlbum::findOrFail($id);
                $album->album_name = $request->album_name;
                $album->save();
            } else {
                $album = WebGalleryAlbum::create([
                    'album_name' => $request->album_name,
                    'album_order'=> 1,
                ]);
            }

            // Handle images
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $file) {
                    $filename = Str::random(10) . '-' . $file->getClientOriginalName();
                    $file->move(public_path('assets/images/websiteImages/'), $filename);

                    WebGalleryImage::create([
                        'gallery_album' => $album->id,
                        'image' => $filename,
                        'is_active' => 1,
                        'home_flag' => 0,
                    ]);
                }
            }

            DB::commit();

            $redirectRoute = $id ? 
            route('admin.gallery-image.module.edit', $id) : 
            route('admin.gallery-image.module.index');

            return redirect()
                ->route($redirectRoute)
                ->with('success', $id ? 'Album updated successfully.' : 'Album created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong! ' . $e->getMessage());
        }
    }
}
