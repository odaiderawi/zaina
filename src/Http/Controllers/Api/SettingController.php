<?php
/**
 * Created by PhpStorm.
 * User: odai
 * Date: 7/5/2019
 * Time: 7:21 PM
 */

namespace Mezian\Zaina\Http\Controllers\Api;

use Mezian\Zaina\Http\Controllers\ZainaController;
use Mezian\Zaina\Models\Setting;

class SettingController extends ZainaController
{

  public function index()
  {
    return Setting::all();
  }

}