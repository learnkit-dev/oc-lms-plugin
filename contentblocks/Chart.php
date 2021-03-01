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
                        'backgroundColor' => [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                        ],
                        'borderColor' => [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                        ],
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