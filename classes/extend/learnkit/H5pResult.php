<?php namespace LearnKit\LMS\Classes\Extend\LearnKit;

use LearnKit\H5p\Models\Result;

class H5pResult
{
    public function subscribe()
    {
        Result::extend(function ($model) {
            $model->bindEvent('model.afterCreate', function () use ($model) {
                $result = new \LearnKit\LMS\Models\Result();
                $result->h5p_result_id = $model->id;
                $result->user_id = auth()->user()->id;
                $result->save();
            });
        });
    }
}