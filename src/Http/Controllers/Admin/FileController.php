<?php

namespace Mezian\Zaina\Http\Controllers\Admin;

use Google\Service\Analytics;
use Intervention\Image\Facades\Image;
use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Http\Requests\FileRequest;
use Mezian\Zaina\Models\File;
use Mezian\Zaina\Models\Photo;
use Spatie\Analytics\Period;

/**
 * @resource Type
 *
 * Type Resource Controller
 */
class FileController extends ZainaController
{

  public $domain;

  public function __construct()
  {
    $this->domain = env( 'APP_URL' );
  }

  public function uploadFiles( FileRequest $request )
  {
    $uploadfile = new File();

    if ( $file = $request->file( 'file' ) )
    {
      $name = $file->getClientOriginalName();
      $ext  = strrchr( $name, '.' );
      $name = time() . '' . str_random( 5 ) . $ext;

      $year_folder  = date( "Y" );
      $month_folder = date( "m" );

      $file->storeAs( '/public/uploads/uploadCenter/' . $year_folder . '/' . $month_folder, $name );
      $uploadfile->url         = 'uploads/uploadCenter/' . $year_folder . '/' . $month_folder . '/' . $name;
      $uploadfile->name        = $file->getClientOriginalName();
      $uploadfile->description = 'upload center';

      $ext = substr( strrchr( $name, '.' ), 1 );

      if ( $ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'bmp' || $ext == 'JPG' || $ext == 'PNG'
           || $ext == 'JPEG' || $ext == 'GIF' || $ext == 'BMP' || $ext == 'svg' || $ext == 'SVG' )
      {
        $uploadfile->type = File::TYPE_PHOTO;
        $uploadfile->save();
      } else if ( $ext == 'avi' || $ext == 'wmv' || $ext == 'mp4' || $ext == 'flv' )
      {
        $uploadfile->type = File::TYPE_VIDEO;
        $uploadfile->save();

      } else
      {
        $uploadfile->type = File::TYPE_FILE;
        $uploadfile->save();
      }

      $filename = 'uploads/uploadCenter/' . $year_folder . '/' . $month_folder . '/' . $name;
      $server   = $this->domain;
      $url      = $server . $filename;

      if ( $uploadfile != null )
      {
        $status = "true";
      } else
      {
        $status = "false";
      }

      return $url;

    }
  }

  public function getAllImages( $year, $month, $count, $type = File::TYPE_PHOTO )
  {

    switch ( $type )
    {
      case 'videos':
        $file_type = File::TYPE_VIDEO;
        break;
      case 'files':
        $file_type = File::TYPE_FILE;
        break;
      default:
        $file_type = File::TYPE_PHOTO;
        break;
    }

    if ( $year == 0 && $month == 0 )
    {
      $photos = File::where( 'type', $file_type )->orderBy( 'id', 'Desc' )->paginate( $count );

    } else if ( $month == 0 )
    {
      $photos = File::whereYear( 'created_at', '=', $year )
                    ->where( 'type', $file_type )
                    ->orderBy( 'id', 'Desc' )
                    ->paginate( $count );

    } else if ( $year == 0 )
    {
      $photos = Photo::whereMonth( 'created_at', $month )
                     ->where( 'type', $file_type )
                     ->orderBy( 'id', 'Desc' )
                     ->paginate( $count );

    } else
    {
      $photos = File::where( 'type', $file_type )
                    ->whereYear( 'created_at', $year )
                    ->whereMonth( 'created_at', $month )
                    ->orderBy( 'id', 'Desc' )
                    ->paginate( $count );
    }

    return $photos;

  }

  public function resize_images( $size, $image )
  {
    list( $width, $height ) = explode( 'x', $size );
    if ( filter_var( $image, FILTER_VALIDATE_INT ) )
    {
      $myimage = storage_path() . '/' . beimg_encode( $image ) . ".jpg";
    } else
    {
      $myimage = public_path() . '/storage/' . $image;
    }
    if ( file_exists( $myimage ) )
    {
      $img = Image::make( $myimage );
    } else
    {
      $img = Image::make( public_path() . '/img/empty-photo.jpg' );
    }

    $img->fit( $width, $height );
    $img->interlace( true );
    //$img->encode('jpg', 65);
    $res = $img->response();
    $res->header( 'Cache-Control', 'public, max-age=86400' );
    $res->header( 'content-type', 'image/jpeg' );
    $res->header( 'Last-Modified', gmdate( 'D, d M Y H:i:s' ) . ' GMT' );

    return $res;
  }

  public function getFileById( $id )
  {
    return File::findOrFail( $id );

  }

  public function test()
  {
    dd( Analytics::fetchMostVisitedPages( Period::days( 7 ) ) );

  }

}
