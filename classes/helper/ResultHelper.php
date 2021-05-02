<?php namespace LearnKit\LMS\Classes\Helper;

use Auth;
use LearnKit\LMS\Models\Page;
use LearnKit\H5p\Models\Result;
use LearnKit\LMS\Models\Course;

class ResultHelper
{
    public static function user()
    {
        return Auth::getUser();
    }

    /**
     * This function gets the score + max score for a given page
     *
     * @param $id
     * @return object
     */
    public static function forPage($id)
    {
        //
        $page = Page::find($id);

        //
        $user = static::user();

        //
        $result = (object) [
            'total' => 0,
            'max' => 0,
        ];

        //
        foreach ($page->content_blocks as $block) {
            $block = (object) $block;

            if ($block->content_block_type === 'learnkit.lms::h5p') {
                // Get the score for a block
                $h5pResult = Result::where('user_id', $user->id)->where('content_id', $block->content_id)->first();

                if ($h5pResult) {
                    $result->max += $h5pResult->max_score;
                    $result->total += $h5pResult->score;
                }
            }
        }

        //
        return $result;
    }

    public static function forBlock($pageId, $blockId)
    {
        //
        $page = Page::find($pageId);

        //
        $user = static::user();

        //
        $result = (object) [
            'total' => 0,
            'max' => 0,
        ];

        //
        $blocks = collect($page->content_blocks);
        $block = $blocks->where('hash', $blockId);

        //
        if (!$block) {
            return;
        }

        //
        $block = (object) $block;

        //
        if ($block->content_block_type === 'learnkit.lms::h5p') {
            // Get the score for a block
            $h5pResult = Result::where('user_id', $user->id)->where('content_id', $block->content_id)->first();

            if (! $h5pResult) {
                return $result;
            }

            $result->max += $h5pResult->max_score;
            $result->total += $h5pResult->score;
        }

        //
        return $result;
    }

    public static function forCourse($id)
    {
        //
        $course = Course::find($id);

        //
        if (!$course) {
            return;
        }

        //
        $user = static::user();

        //
        $result = (object) [
            'total' => 0,
            'max' => 0,
        ];

        // Loop through all the pages
        foreach ($course->pages as $page) {
            // Loop through all the content blocks
            foreach ($page->content_blocks as $block) {
                $block = (object) $block;

                if ($block->content_block_type === 'learnkit.lms::h5p') {
                    // Get the score for a block
                    $h5pResult = Result::where('user_id', $user->id)->where('content_id', $block->content_id)->first();

                    if ($h5pResult) {
                        $result->max += $h5pResult->max_score;
                        $result->total += $h5pResult->score;
                    }
                }
            }
        }

        return $result;
    }
}