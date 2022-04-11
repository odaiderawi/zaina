<?php

namespace Mezian\Zaina\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
  /**
   * Transform the resource collection into an array.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return array
   */
  public function toArray( $request )
  {
    return [
      'id'              => $this->id,
      'slug'            => $this->slug,
      'title'           => $this->title,
      'highlight_title' => $this->highligh_title,
      'image'           => $this->image,
      'category'        => [
        'id'   => $this->category->id,
        'name' => $this->category->name,
        'slug' => $this->category->slug,
      ],
      'type'            => $this->whenLoaded( 'type', function () {
        return $this->name;
      } ),
      //            'meta' => $this->meta
    ];
  }
}
