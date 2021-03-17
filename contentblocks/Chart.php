<?php namespace LearnKit\LMS\ContentBlocks;

use Auth;
use LearnKit\LMS\Models\SubjectResult;
use LearnKit\LMS\Classes\Base\ContentBlockBase;

class Chart extends ContentBlockBase
{
    public static $code = 'learnkit.lms::chart';

    public static $label = 'Chart';

    public static $description = 'Draws a chart';

    public function getChartData()
    {
        // Prepare chart data
        $userId = Auth::getUser()->id;
        $sections = collect($this->config['sections']);
        $scores = [];

        //
        $colors = collect($this->config['colors'])->pluck('color')->toArray();

        foreach ($sections as $section) {
            // Get score for this section
            $score = SubjectResult::where('user_id', $userId)
                ->where('subject', $section['section_key'])
                ->get()
                ->sum('score');

            $scores[] = $score;
        }

        return json_encode([
            'type' => 'pie',
            'data' => [
                'labels' => $sections->pluck('section_label')->toArray(),
                'datasets' => [
                    [
                        'label' => '',
                        'data' => $scores,
                        'backgroundColor' => $colors,
                        'borderColor' => $colors,
                        'borderWidth' => 1,
                    ],
                ],
            ],
            'options' => [
                'scales' => [
                    'yAxes' => [
                        [
                            'display' => (boolean) $this->config['show_y'],
                            'ticks' => [
                                'beginAtZero' => true,
                            ],
                        ]
                    ],
                    'xAxes' => [
                        [
                            'display' => (boolean) $this->config['show_x'],
                        ],
                    ],
                ],
            ],
        ]);
    }
}