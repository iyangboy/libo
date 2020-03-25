<?php

namespace App\Http\Requests\Api;


class ImageCardRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'type' => 'required|string|in:front,back',
            // 'image' => 'required|mimes:jpeg,bmp,png,gif,jpg',
            // 'base64' => 'required|mimes:jpeg,bmp,png,gif,jpg',
        ];

        // if ($this->type == 'avatar') {
        //     $rules['image'] = 'required|mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200';
        // } else {
        //     $rules['image'] = 'required|mimes:jpeg,bmp,png,gif,jpg';
        // }

        return $rules;
    }
}
