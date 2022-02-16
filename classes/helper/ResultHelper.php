<?php namespace LearnKit\LMS\Classes\Helper;

use Auth;
use LearnKit\LMS\Models\Page;
use LearnKit\H5p\Models\Result;
use LearnKit\LMS\Models\Course;
use LearnKit\H5p\Models\Content;

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
    public static function forPage($id, $user = null)
    {
        //
        $page = Page::find($id);

        //
        if (! $user) {
            $user = static::user();
        }

        //
        $result = (object) [
            'total' => 0,
            'max' => 0,
            'status' => true,
        ];

        //
        foreach ($page->content_blocks as $block) {
            $block = (object) $block;

            if ($block->content_block_type === 'learnkit.lms::h5p') {
                // Get the H5P content
                $content = Content::find($block->content_id);

                // Get the score for a block
                $h5pResult = Result::where('user_id', $user->id)->where('content_id', $block->content_id)->first();

                $result->max += $content->max_score;

                if ($h5pResult) {
                    $result->total += $h5pResult->score;
                } else {
                    $result->status = false;
                }
            }
        }

        //
        return $result;
    }

    public static function forBlock($pageId, $blockId, $user = null)
    {
        //
        $page = Page::find($pageId);

        //
        if (! $user) {
            $user = static::user();
        }

        //
        $result = (object) [
            'total' => 0,
            'max' => 0,
            'status' => true,
        ];

        if (!$page) {
            return $result;
        }

        //
        $blocks = collect($page->content_blocks);
        $block = $blocks->where('hash', $blockId)->first();

        //
        if (!$block) {
            return;
        }

        //
        $block = (object) $block;

        //
        if ($block->content_block_type === 'learnkit.lms::h5p') {
            // Get the score for a block
            $content = Content::find($block->content_id);

            $h5pResult = Result::where('user_id', $user->id)->where('content_id', $block->content_id)->first();

            $result->max += $content->max_score;

            if (! $h5pResult) {
                $result->status = false;
                return $result;
            }

            $result->total += $h5pResult->score;
        }

        //
        return $result;
    }

    public static function forCourse($id, $user = null)
    {
        //
        $course = Course::find($id);

        //
        if (!$course) {
            return;
        }

        //
        if (! $user) {
            $user = static::user();
        }

        //
        $result = (object) [
            'total' => 0,
            'max' => 0,
            'percentageDone' => 0,
            'status' => true,
        ];

        $maxH5pItemsDone = 0;
        $done = 0;

        // Loop through all the pages
        foreach ($course->pages()->where('exclude_from_export', 0)->get() as $page) {
            // Loop through all the content blocks
            if (!$page->content_blocks) {
                continue;
            }

            foreach ($page->content_blocks as $block) {
                $block = (object) $block;

                if ($block->content_block_type === 'learnkit.lms::h5p') {
                    $content = Content::find($block->content_id);

                    $maxH5pItemsDone++;

                    // Get the score for a block
                    $h5pResult = Result::where('user_id', $user->id)->where('content_id', $block->content_id)->first();

                    if ($h5pResult) {
                        $done++;
                    }

                    if ($h5pResult) {
                        $result->total += $h5pResult->score;
                    }

                    if (!$h5pResult) {
                        $result->status = false;
                    }

                    $result->max += $content->max_score;
                }
            }
        }

        if ($maxH5pItemsDone > 0) {
            $result->percentageDone = floor($done / $maxH5pItemsDone * 100);
        }

        return $result;
    }
}