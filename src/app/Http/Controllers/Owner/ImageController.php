<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class ImageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:owners');
        // ログインownerの子image以外なら404にする
        $this->middleware(function($request, $next) {
            $id = $request->route()->parameter('image');
            if (!is_null($id)) {
                $image = Image::findOrFail($id);
                if( (int)$image->owner->id !== Auth::id()) abort(404);
            }
            return $next($request);
        });
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::where('owner_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('owner.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        $imageFileRows = $request->file('files');
        if (!is_null($imageFileRows)) {
            foreach($imageFileRows as $imageFileRow) {
                $fileNameToStore = imageService::upload($imageFileRow['image'], 'products');
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore,
                ]);
            }
        }

        return redirect()
            ->route('owner.images.index')
            ->with([
                'message' => '画像登録を実施しました。',
                'status' => 'info',
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $image = Image::findOrFail($id);
        return view('owner.images.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'string|max:50',
        ]);

        $image = Image::findOrFail($id);
        $image->title = $request->title;
        $image->save();

        return redirect()
            ->route('owner.images.index')
            ->with([
                'message' => '画像情報を更新しました。',
                'status' => 'info',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = Image::FindOrFail($id);

        // Productのimage1〜4の値が削除imageならnullにしておく
        $imageInProducts = Product::where('image1', $image->id)
            ->orWhere('image2', $image->id)
            ->orWhere('image3', $image->id)
            ->orWhere('image4', $image->id)
            ->get();

        if ($imageInProducts) {
            $imageInProducts->each( function($product) use ($image) {
                if($product->image1 === $image->id) {
                    $product->image1 = null;
                    $product->save();
                }
                if($product->image2 === $image->id) {
                    $product->image2 = null;
                    $product->save();
                }
                if($product->image3 === $image->id) {
                    $product->image3 = null;
                    $product->save();
                }
                if($product->image4 === $image->id) {
                    $product->image4 = null;
                    $product->save();
                }
            });
        }

        $filePath = 'public/products/' . $image->filename;
        if (Storage::exists($filePath)) Storage::delete($filePath);

        $image->delete();

        return redirect()
            ->route('owner.images.index')
            ->with([
                'message' => '画像を削除しました。',
                'status' => 'alert',
            ]);
    }
}
