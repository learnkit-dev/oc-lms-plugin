<?php

Route::get('/lms/report/{courseId}/{pageId}/{content_block_hash}', function ($courseId, $pageId, $contentBlockHash) {
    $course = \LearnKit\LMS\Models\Course::find($courseId);
    $page = \LearnKit\LMS\Models\Page::find($pageId);
    $contentBlock = collect($page->content_blocks)
        ->where('hash', $contentBlockHash)
        ->first();

    $payload = [];
    $user = Auth::getUser();

    //
    if (!$user) {
        return redirect('/');
    }

    // Get all results
    $results = $user->results()->where('course_id', $courseId)->get();
    $results = $results->groupBy('content_block_hash')->map(function ($item) {
        $items = $item->pluck('payload')->toArray();

        return [
            'items' => $items,
            'last' => $items[count($items)-1],
            'first' => $items[0],
        ];
    });

    // Prepare charts
    $charts = [];
    $chartData = [];
    foreach ($contentBlock['charts'] as $chartItem) {
        // Get the data
        $scores = [];
        $colors = collect($chartItem['colors'])->pluck('color')->toArray();

        foreach (collect($chartItem['subjects']) as $section) {
            $score = \LearnKit\LMS\Models\SubjectResult::where('user_id', Auth::getUser()->id)
                ->where('subject', $section['key'])
                ->get()
                ->sum('score');

            $scores[] = $score;
        }

        // Chart options
        $options = [
            'legend' => [
                'display' => (boolean) $chartItem['show_legend'],
            ],
            'scales' => [
                'yAxes' => [[
                    'display' => (boolean) $chartItem['show_y'],
                    'ticks' => [
                        'beginAtZero' => true,
                        'stepSize' => 1,
                    ],
                ]],
                'xAxes' => [[
                    'display' => (boolean) $chartItem['show_x'],
                ]],
            ],
        ];
        $options = new \Bbsnly\ChartJs\Config\Options($options);

        //
        $chart = new \Bbsnly\ChartJs\Chart();
        $chart->type = $chartItem['type'];
        $chart->options($options);
        $data = new \Bbsnly\ChartJs\Config\Data();
        $labels = collect($chartItem['subjects'])->pluck('label', 'key')->toArray();

        $dataSet = (new \Bbsnly\ChartJs\Config\Dataset())->data($scores)->label('Result');

        $dataSet->backgroundColor = $colors;
        $dataSet->borderColor = $colors;
        $dataSet->borderWidth = 1;

        $datasets = [
            $dataSet,
        ];

        $data->datasets($datasets)->labels(array_values($labels));

        $chart->data($data);

        $charts[] = $chart->toJson();
    }

    //
    $data = [
        'result' => $results,
        'charts' => $charts,
        'user' => Auth::getUser(),
    ];

    // Run custom PHP code if any
    if ($contentBlock['custom_php']) {
        eval($contentBlock['custom_php']);
    }

    $data['payload'] = $payload;

    $pdf = \Renatio\DynamicPDF\Classes\PDF::loadTemplate($contentBlock['pdf_code'], $data);

    if ($contentBlock['mode'] == 'stream') {
        return $pdf->stream($contentBlock['file_name']);
    } else {
        return $pdf->download($contentBlock['file_name']);
    }
})->middleware('web');

Route::get('/h5p_override_styles.css', function () {
    return response(\Cms\Classes\Theme::getActiveTheme()->getCustomData()->custom_css)
        ->header('Content-Type', 'text/css');
});