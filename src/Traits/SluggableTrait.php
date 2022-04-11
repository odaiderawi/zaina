<?php

namespace Mezian\Zaina\Traits;

use Cocur\Slugify\Slugify;
use Cviebrock\EloquentSluggable\Sluggable;

trait SluggableTrait
{
  use Sluggable;

  public function customizeSlugEngine( Slugify $engine, $attribute )
  {
    $engine = new Slugify( [ 'regexp' => '/([^\p{Arabic}a-zA-Z0-9]+|-+)/u', 'rulesets' => [ 'default', 'arabic' ] ] );
    $engine->addRules( [
                         'أ' => 'أ',
                         'ب' => 'ب',
                         'ت' => 'ت',
                         'ث' => 'ث',
                         'ج' => 'ج',
                         'ح' => 'ح',
                         'خ' => 'خ',
                         'د' => 'د',
                         'ذ' => 'ذ',
                         'ر' => 'ر',
                         'ز' => 'ز',
                         'س' => 'س',
                         'ش' => 'ش',
                         'ص' => 'ص',
                         'ض' => 'ض',
                         'ط' => 'ط',
                         'ظ' => 'ظ',
                         'ع' => 'ع',
                         'غ' => 'غ',
                         'ف' => 'ف',
                         'ق' => 'ق',
                         'ك' => 'ك',
                         'ل' => 'ل',
                         'م' => 'م',
                         'ن' => 'ن',
                         'ه' => 'ه',
                         'و' => 'و',
                         'ي' => 'ي',
                         'ّ' => '',
                         'َ' => '',
                         'ً' => '',
                         'ُ' => '',
                         'ٌ' => '',
                         'ِ' => '',
                         'ٍ' => '',
                       ] );

    return $engine;

  }
}
