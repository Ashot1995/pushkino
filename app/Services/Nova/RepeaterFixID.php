<?php

namespace App\Services\Nova;

use Laravel\Nova\Fields\Text;

class RepeaterFixID extends Text
{
    public static function generate()
    {
        return self::make('Служебный ID', 'uid')
            ->placeholder('Генерируется автоматически')
            ->readonly();
    }
}
