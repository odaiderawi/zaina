<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Http\Requests\ArticleRequest;
use Mezian\Zaina\Http\Resources\Admin\ArticleResource;
use Mezian\Zaina\Models\Article;

/**
 * @resource News
 *
 * News Resource Controller
 */
class ArticleController extends ZainaController
{
  public $domain;

  public function __construct()
  {
    $this->domain = env( 'APP_URL' ) . '/article';
  }

  public function index()
  {
    return Article::orderBy( 'created_at', 'DESC' )
                  ->paginate( 32 );
  }

  public function show( $id )
  {
    /** @var Article $article */
    ArticleResource::withoutWrapping();
    $article = new ArticleResource( Article::findOrFail( $id ) );

    return $article;

  }

  public function draft( ArticleRequest $request )
  {
    return $request->articles();
  }

  public function store( ArticleRequest $request )
  {
    $this->draft( $request )->update( [ 'is_draft' => 0 ] );

  }

  public function update( ArticleRequest $request )
  {
    $this->draft( $request )->update( [ 'is_draft' => 0 ] );

  }

  public function destroy( $id )
  {
    Article::findOrFail( $id )->delete();

    return 'article deleted successfully';
  }

  public function status( $id )
  {
    /** @var Article $article */
    $articles = Article::findOrFail( $id );

    $articles->update( [ 'is_disabled' => ! $articles->is_disabled ] );

    return 'article status set to ' . $articles->is_disabled;

  }

  public function last_articles( $number )
  {
    return Article::where( 'date_to_publish', '<', Carbon::now() )
                  ->where( 'is_draft', 0 )
                  ->orderBy( 'created_at', 'DESC' )
                  ->take( $number )
                  ->get();

  }

  public function search( Request $request )
  {
    $data = $request->all();

    if ( ! is_null( $data['id'] ) || $data['id'] != '' )
    {
      return Article::where( 'id', intval( $data['id'] ) )->paginate( 1 );
    }

    /** @var Article $articles */
    $articles = Article::search( $data )
                       ->orderBy( 'id', 'DESC' )
                       ->paginate( 32 );

    return $articles;

  }

}
