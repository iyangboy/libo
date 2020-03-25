<?php

namespace App\Http\Controllers\Api;

use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandler;
use App\Http\Requests\Api\ImageCardRequest;
use App\Http\Resources\ImageResource;
use App\Http\Requests\Api\ImageRequest;
use App\Http\Resources\PrivateUserResource;

class ImagesController extends Controller
{
    public function store(ImageRequest $request, ImageUploadHandler $uploader, Image $image)
    {
        $user = $request->user();

        $size = $request->type == 'avatar' ? 416 : 1024;
        $result = $uploader->save($request->image, Str::plural($request->type), $user->id, $size);

        $image->path = $result['path'];
        $image->type = $request->type;
        $image->user_id = $user->id;
        $image->save();

        return new ImageResource($image);
    }

    public function imageCard(ImageCardRequest $request, ImageUploadHandler $uploader, Image $image)
    {
        $user = $request->user();
        $type = $request->type ?? '';
        $base64 = $request->base64 ?? '';

        // $size = 750;
        // $result = $uploader->save($request->image, Str::plural($request->type), $user->id, $size);

        if ($type == 'front') {
            // $user->card_front_path = $result['path'];
            $user->card_front_path = $base64;
        } else if ($type == 'back') {
            // $user->card_back_path = $result['path'];
            $user->card_back_path = $base64;
        }
        $user->save();

        return response()->json([
            'data'    => new PrivateUserResource($user)
        ], 200);
    }
}
