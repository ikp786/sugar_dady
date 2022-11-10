<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;


class UserImageCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($this);
        return [
            'image_name' =>!empty($this->image_name) ? asset('public/storage/user_images/'.$this->image_name) : ''
        ];
        die;
        $data = array();
        if (isset($this[0]->image_name)) {
            foreach ($this as $key => $value) {
                if (isset($value->image_name) && $value->image_name != '') {
                    $data[] = !empty($value->image_name) ? asset('storage/app/public/user_images/'.$value->image_name) : '';                   
                }                
            }
        }
        return $data;     
    }
}