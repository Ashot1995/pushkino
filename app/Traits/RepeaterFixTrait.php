<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait RepeaterFixTrait
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        self::updating(function (Model $model) {
            if (!is_null($this->repeaters)) {
                foreach ($this->repeaters as $column => $files) {
                    $repeater = $model->$column;
                    $originalRepeater = $model->getOriginal($column);

                    if (!is_null($repeater)) {
                        foreach ($repeater as &$element) {
                            $uidColumn = 'fields.uid';
                            $uid = Arr::get($element, $uidColumn);

                            if (is_null($uid)) {
                                Arr::set($element, $uidColumn, Str::uuid()->toString());
                            }

                            $originalElement = collect($originalRepeater)->firstWhere($uidColumn, $uid);

                            if (!is_null($originalElement)) {
                                foreach ($files as $file) {
                                    $fileColumn = 'fields.' . $file;

                                    if (is_null(Arr::get($element, $fileColumn))) {
                                        Arr::set($element, $fileColumn, Arr::get($originalElement, $fileColumn));
                                    }
                                }
                            }
                        }
                    }

                    $model->$column = $repeater;
                }
            }

        });
    }
}
