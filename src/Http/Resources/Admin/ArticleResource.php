<?php

namespace Mezian\Zaina\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
      'author'                => $this->author ? [ $this->author ] : $this->author,
      'author_id'             => $this->author_id,
      'category'              => $this->category,
      'category_id'           => $this->category_id,
      'content'               => $this->content,
      'created_at'            => $this->created_at->format( 'Y-m-d' ),
      'created_by'            => $this->created_by,
      'date_to_publish'       => $this->date_to_publish,
      'deleted_at'            => $this->deleted_at,
      'highlight_title'       => $this->highlight_title,
      'id'                    => $this->id,
      'image'                 => $this->image,
      'image_description'     => $this->image_description,
      'is_archived'           => $this->is_archived,
      'is_article_ticker'     => $this->is_article_ticker,
      'is_disabled'           => $this->is_disabled,
      'is_draft'              => $this->is_draft,
      'is_main_article'       => $this->is_main_article,
      'is_particular_article' => $this->is_particular_article,
      'is_share_to_facebook'  => $this->is_share_to_facebook,
      'is_share_to_twitter'   => $this->is_share_to_twitter,
      'is_shown_in_template'  => $this->is_shown_in_template,
      'is_special_article'    => $this->is_special_article,
      'metas'                 => $this->metas,
      'modified_by'           => $this->modified_by,
      'no_of_views'           => $this->no_of_views,
      'publisher'             => $this->publisher,
      'slug'                  => $this->slug,
      'source'                => $this->source,
      'summary'               => $this->summary,
      'tags'                  => $this->tags,
      'title'                 => $this->title,
      'updated_at'            => $this->updated_at->format( 'Y-m-d' ),
      'use_watermark'         => $this->use_watermark,
    ];
  }
}
