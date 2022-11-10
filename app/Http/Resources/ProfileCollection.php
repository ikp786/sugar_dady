<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\UserImageCollection;


class ProfileCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {               
        return [
            'user_id'                => $this->user_id,
            'full_name'              => $this->full_name,
            'email_address'          => $this->email_address,            
            'mobile_number'          => $this->mobile_number,
            'intrestintrested_ids'   => $this->intrested_ids,   
            'about_me'               => $this->about_me,
            'user_bio'               => $this->user_bio,
            'user_location'          => $this->user_locaion,
            'gender'                 => isset($this->gender->misc_title) ? $this->gender->misc_title : '',
            'sexcual_orientation'    => isset($this->sexcualOrientation->misc_title) ? $this->sexcualOrientation->misc_title : '',
            'user_images'            => UserImageCollection::collection($this->userImages),
        ];
    }
}
