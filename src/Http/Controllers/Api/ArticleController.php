<?php

namespace Mezian\Zaina\Http\Controllers\Api;

use Mezian\Zaina\App\Http\Resources\Admin\ArticleResource;
use Mezian\Zaina\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Mezian\Zaina\App\Http\Controllers\ZainaController;

/**
 * @resource Articles
 *
 * Articles Resource Controller
 */
class ArticleController extends ZainaController
{
  /**
   * Display a listing of all articles with filters.
   *
   * Can get all live articles with default pagination 10
   * Can get live articles by category or by related (every one alone , or multiple)
   *
   * * Without parameters return live articles with 10 articles in page
   *
   * * get parameters :
   * * * String $category_id (for get articles by category id)
   * * * String $category_slug (alternate for category id for get articles by category slug)
   * * * * Integer $category_articles_per_page : default $per_page
   *
   * * * Integer $related_to_id (for get related articles by article id)
   * * * String $related_to_slug (alternate for related_to_id for get related articles by article slug)
   * * * * Integer $related_articles_per_page : default $per_page
   *
   * @param Request $request
   *
   * @return \Illuminate\Http\Response
   */
  public function index( Request $request )
  {
    $articles = [];
    $per_page = $request->input( 'per_page' ) ? $request->input( 'per_page' ) : 10;

    $categorySlug            = $request->input( 'category_slug' );
    $categoryId              = $request->input( 'category_id' );
    $categoryArticlesPerPage = $request->input( 'category_articles_per_page' ) ? $request->input( 'category_articles_per_page' ) : $per_page;

    $relatedToId            = $request->input( 'related_to_id' );
    $relatedToSlug          = $request->input( 'related_to_slug' );
    $relatedArticlesPerPage = $request->input( 'related_articles_per_page' ) ? $request->input( 'related_articles_per_page' ) : $per_page;

    if ( $categoryId )
    {
      $articles['by_category'] = Article::getByCategory( $categoryId )->paginate( $categoryArticlesPerPage );
      $articles['by_category']->appends( Input::only( 'per_page', 'category_id', 'category_articles_per_page' ) );
    } else if ( $categorySlug )
    {
      $articles['by_category'] = Article::getByCategorySlug( $categorySlug )->paginate( $categoryArticlesPerPage );
      $articles['by_category']->appends( Input::only( 'per_page', 'category_slug', 'category_articles_per_page' ) );
    }

    if ( $relatedToId )
    {
      $relatedToArticle = Article::find( $relatedToId );
      if ( ! $relatedToArticle )
      {
        return $this->makeErrorResponse( $request, 404, 'Not Found !' );
      }
      $articles['related'] = $relatedToArticle->getRelated( Article::class )->paginate( $relatedArticlesPerPage );
      $articles['related']->appends( Input::only( 'per_page', 'related_to_id', 'related_articles_per_page' ) );
    } else if ( $relatedToSlug )
    {
      $relatedToArticle = Article::where( 'slug', $relatedToSlug )->first();
      if ( ! $relatedToArticle )
      {
        return $this->makeErrorResponse( $request, 404, 'Not Found !' );
      }
      $articles['related'] = $relatedToArticle->getRelated( Article::class )->paginate( $relatedArticlesPerPage );
      $articles['related']->appends( Input::only( 'per_page', 'related_to_slug', 'related_articles_per_page' ) );
    }

    if ( count( $articles ) == 0 )
    {
      $articles = Article::live()->paginate( $per_page );
      $articles->appends( Input::only( 'per_page' ) );
    }

    return collect( $articles );
  }

  /**
   * Display the single article.
   *
   * @param  $id \Mezian\Zaina\app\Models\Article $article
   *
   * @return \Illuminate\Http\Response
   */
  public function show( $id )
  {
    $data = Article::where( 'id', $id )
                   ->live()
                   ->first();

    if ( $data )
    {
      return new ArticleResource( $data );
    }

  }

}
