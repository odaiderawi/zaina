<?php

namespace Mezian\Zaina\Utils;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Response
 *
 * @package App
 *
 */
class Response extends JsonResponse
{
  const HTTP_OK_WITH_WARNING        = 299;
  const STATUS_OK_WITH_WARNING_TEXT = 'OK With Warning';

  const CODE_UNRESOLVABLE_IMAGE = 'UnresolvableImage';
  const CODE_UNAUTHORIZED       = 'Unauthorized';
  const CODE_FORBIDDEN          = 'Forbidden';
  const CODE_JWT_KEY_MESSING    = 'JwtKeyMissing';
  const CODE_NOT_FOUND          = 'NotFound';
  const CODE_METHOD_NOT_ALLOWED = 'MethodNotAllowed';
  const CODE_RESOURCE_LINKED    = 'IsLinked';
  const CODE_UNEXPECTED         = 'Unexpected';
  const CODE_EMPTY              = 'IsEmpty';
  const CODE_NOT_LEAF           = 'IsNonLeaf';
  const CODE_UNACCEPTABLE       = 'IsUnacceptable';
  const CODE_INVALID_ROW        = 'InvalidRow';
  const CODE_INVALID_Template   = 'InvalidTemplate';
  const CODE_FILE_NOT_FOUND     = 'FileNotFound';
  const CODE_IS_LOCKED          = 'IsLocked';

  /**
   * @var Response
   */
  private static $instance = null;

  /**
   * Get/Create a new Instance
   *
   * @param int $statusCode
   *
   * @param bool $newInstance
   *
   * @return Response
   */
  public static function make( int $statusCode = null, bool $newInstance = false )
  {
    if ( ! static::$instance || $newInstance )
    {
      static::$instance = new self( null, $statusCode ?: self::HTTP_OK );
    }

    // set status code if sent after first creation
    if ( $statusCode )
    {
      static::$instance->setStatusCode( $statusCode );
    }

    return static::$instance;
  }

  /**
   * Get Instance of response
   *
   * @param int $statusCode
   *
   * @return Response
   */
  public static function instance( int $statusCode = null )
  {
    return self::make( $statusCode );
  }

  /**
   * @param $data
   * @param string|null $key
   *
   * @return Response
   */
  public function addData( $data, string $key = null )
  {
    if ( ! isset( $this->original['data'] ) )
    {
      $this->original['data'] = [];
    }

    if ( $key )
    {
      $this->original['data'][ $key ] = $data;
    } else
    {
      $this->original['data'] = $data;
    }

    return $this;
  }

  /**
   * @param string $message
   *
   * @return Response
   */
  public function setMessage( string $message )
  {
    $this->original['message'] = $message;

    return $this;
  }

  /**
   * Set Errors to response
   *
   * @param array $errors
   *
   * @return Response
   */
  public function setErrors( array $errors )
  {
    $this->original['errors'] = $errors;

    return $this;
  }

  /**
   * Add error to response errors
   *
   * @param string $message
   * @param string $code
   * @param string|null $key
   * @param array $data
   * @param bool $prepare
   *
   * @return Response
   */
  public function addError( string $message, string $code, string $key, array $data = [], $prepare = false )
  {
    if ( ! isset( $this->original['errors'] ) )
    {
      $this->original['errors'] = [];
    }

    if ( ! isset( $this->original['errors'][ $key ] ) )
    {
      $this->original['errors'][ $key ] = [];
    }

    $this->original['errors'][ $key ][] = [ 'message' => $message, 'code' => $code, 'data' => $data ];

    // for unknown reason sometimes response does not call "prepare" function !!
    if ( $prepare )
    {
      $this->prepare( request() );
    }

    return $this;
  }

  /**
   * Set warnings to response
   *
   * @param array $warnings
   *
   * @return Response
   */
  public function setWarnings( array $warnings )
  {
    $this->original['warnings'] = $warnings;

    return $this;
  }

  /**
   * Add warning to response warnings
   *
   * @param string $message
   * @param string $code
   * @param string $key
   * @param array $data
   *
   * @return Response
   */
  public function addWarning( string $message, string $code, string $key, array $data = [] )
  {
    if ( ! isset( $this->original['warnings'] ) )
    {
      $this->original['warnings'] = [];
    }

    if ( ! isset( $this->original['warnings'][ $key ] ) )
    {
      $this->original['warnings'][ $key ] = [];
    }

    $this->original['warnings'][ $key ][] = [ 'message' => $message, 'code' => $code, 'data' => $data ];

    return $this;
  }

  /**
   * @param \Illuminate\Contracts\Validation\Validator|array $validator
   * @param string|null $prefix
   */
  public function addWarningsFromValidator( $validator, string $prefix = null )
  {
    $errors = is_array( $validator ) ? $validator : $validator->errors()->toArray();
    foreach ( $errors as $key => $errors )
    {
      foreach ( $errors as $error )
      {
        $this->addWarning( $error['message'], $error['code'], $prefix ? "$prefix.$key" : $key, $error['data'] ?? [] );
      }
    }
  }

  /**
   * Set as success response
   *
   * @param int $statusCode
   * @param string $message
   *
   * @return Response
   */
  public function success( $statusCode = self::HTTP_OK, string $message = '' )
  {
    $this->setStatusCode( $statusCode );

    if ( ! empty( $message ) )
    {
      $this->setMessage( $message );
    }

    return $this;
  }

  /**
   * Set as warning response
   *
   * @param int $statusCode
   * @param array $warnings
   *
   * @return Response
   */
  public function warning( $statusCode = self::HTTP_OK_WITH_WARNING, array $warnings = [] )
  {
    if ( ! empty( $warnings ) )
    {
      $this->setWarnings( $warnings );
    }

    return $this->setStatusCode( $statusCode, self::STATUS_OK_WITH_WARNING_TEXT );
  }

  /**
   * @param int $statusCode
   * @param array $errors
   *
   * @return Response
   */
  public function error( $statusCode = self::HTTP_INTERNAL_SERVER_ERROR, array $errors = [] )
  {
    if ( ! empty( $errors ) )
    {
      $this->setErrors( $errors );
    }

    return $this->setStatusCode( $statusCode );
  }

  /**
   * Prepares the Response before it is sent to the client.
   *
   * @param Request $request
   *
   * @return JsonResponse
   */
  public function prepare( Request $request )
  {
    $this->setStatus();

    $this->setData( $this->original );

    // return status code 200 #SKU-847
    if ( $this->statusCode < 300 )
    {
      $this->setStatusCode( self::HTTP_OK );
    }

    return parent::prepare( $request );
  }

  /**
   * Set status to response depends on data
   */
  private function setStatus()
  {
    if ( ! empty( $this->original['errors'] ) )
    {
      $this->original['status'] = __( 'messages.status_failure' );

      if ( empty( $this->original['message'] ) )
      {
        $this->original['message'] = $this->original['errors'][ array_key_first( $this->original['errors'] ) ][0]['message'];
      }
    } else if ( ! empty( $this->original['warnings'] ) )
    {
      $this->setStatusCode( self::HTTP_OK_WITH_WARNING, self::STATUS_OK_WITH_WARNING_TEXT );

      $this->original['status'] = __( 'messages.status_warning' );

      if ( empty( $this->original['message'] ) )
      {
        $this->original['message'] = $this->original['warnings'][ array_key_first( $this->original['warnings'] ) ][0]['message'];
      }
    } else
    {
      $this->original['status'] = __( 'messages.status_success' );
    }
  }

  /**
   * @param null $key
   *
   * @param null $default
   *
   * @return mixed
   */
  public function getOriginal( $key = null, $default = null )
  {
    if ( $key )
    {
      return $this->original['data'][ $key ] ?? $default;
    }

    return $this->original;
  }
}
