<?php

namespace Mezian\Zaina\Http\ViewComposers;

use Illuminate\View\View;
use Mezian\Zaina\Models\Setting;

class SettingsComposer
{
  public $settings;

  /**
   * Create a movie composer.
   *
   * @return void
   */
  public function __construct()
  {
    $this->settings = Setting::pluck( 'value', 'key' )->all();
  }

  /**
   * Bind data to the view.
   *
   * @param View $view
   *
   * @return void
   */
  public function compose( View $view )
  {
    $view->with( 'zaina-settings', $this->settings );
  }
}