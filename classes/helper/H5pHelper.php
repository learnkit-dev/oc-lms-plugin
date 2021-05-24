<?php namespace LearnKit\LMS\Classes\Helper;

use RainLab\User\Models\User;
use LearnKit\H5p\Models\Result;
use LearnKit\H5p\Models\Content;

class H5pHelper
{
    public static function getContentById($id): ?Content
    {
        return Content::find($id);
    }

    public static function percentageById($id)
    {
        $userIds = Result::all()->groupBy('user_id')->keys()->toArray();

        $users = User::whereIn('id', $userIds)->get();

        //
        $resultCount = 0;
        $maxScore = 0;

        //
        foreach ($users as $user) {
            // Check if result is max score
            $content = Content::find($id);

            $result = Result::where('content_id', $id)
                ->where('user_id', $user->id)
                ->orderBy('score', 'desc')
                ->first();

            if (!$result) {
                $maxScore++;
            }

            if ($result && $result->score === $content->max_score) {
                $maxScore++;
            }

            if ($result) {
                $resultCount++;
            }
        }

        if ($resultCount < 1) {
            return 0;
        }

        return floor($maxScore / $resultCount * 100);
    }
}