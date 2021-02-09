<?php

function is_exists($element, $data)
{
    if (isset($data[$element]) && !empty($data[$element])) {
        return $data[$element];
    }
}

function createPagination($config)
{
    $html = '';
    $get = '';

    if ($config['cur_page'] > $config['max_page'] || $config['max_page'] == 1) return $html;

    if (!empty($config['get'])) {
        $get = array();
        foreach ($config['get'] as $index => $key) {
            $get[] = "$index=$key";
        }
        $get = '?' . implode('&', $get);
    }

    if ($config['cur_page'] > $config['num_links'] + 1) { // first page is not visible
        $html .= $config['first_tag_open'] . '<a class="' . $config['tag_classes'] . '" href="' . $config['url'] . $get . '">' . $config['first_link'] . '</a>' . $config['first_tag_close'];
    }

    if ($config['cur_page'] > 1) { // is not first page
        $html .= $config['prev_tag_open'] . '<a class="' . $config['tag_classes'] . '" href="' . $config['url'] . ($config['cur_page'] - 1) . $get . '">' . $config['prev_link'] . '</a>' . $config['prev_tag_close'];
    }

    $start = $config['cur_page'] - $config['num_links'];
    $finish = $start + ($config['num_links'] * 2 + 1);

    for ($i = $start; $i < $finish; ++$i) {
        if ($i < 1 || $i > $config['max_page']) continue; // 

        if ($config['cur_page'] == $i) {
            $html .= $config['cur_tag_open'] . $i . $config['cur_tag_close'];
        } else {
            $html .= $config['num_tag_open'] . '<a class="' . $config['tag_classes'] . '" href="' . $config['url'] . $i . $get . '">' . $i . '</a>' . $config['num_tag_close'];
        }
    }

    if ($config['cur_page'] < $config['max_page']) { // is not last page
        $html .= $config['next_tag_open'] . '<a class="' . $config['tag_classes'] . '" href="' . $config['url'] . ($config['cur_page'] + 1) . $get . '">' . $config['next_link'] . '</a>' . $config['next_tag_close'];
    }

    if ($config['cur_page'] < $config['max_page'] - $config['num_links']) { // last page is not visible
        $html .= $config['last_tag_open'] . '<a class="' . $config['tag_classes'] . '" href="' . $config['url'] . $config['max_page'] . $get . '">' . $config['last_link'] . '</a>' . $config['last_tag_close'];
    }

    return $html;
}

function taskPagination()
{
    return [
        'cur_tag_open' => '<li class="page-item active"><a class="page-link" href="javascript:void(0)">',
        'cur_tag_close' => '</a></li>',

        'num_tag_open' => '<li class="page-item">',
        'num_tag_close' => '</li>',

        'next_tag_open' => '<li class="page-item">',
        'next_tag_close' => '</li>',

        'prev_tag_open' => '<li class="page-item">',
        'prev_tag_close' => '</li>',

        'first_tag_open' => '<li class="page-item">',
        'first_tag_close' => '</li>',

        'last_tag_open' => '<li class="page-item">',
        'last_tag_close' => '</li>',

        'prev_link' => '<i class="fas fa-angle-left"></i>',
        'next_link' => '<i class="fas fa-angle-right"></i>',

        'first_link' => '<i class="fas fa-angle-double-left"></i>',
        'last_link' => '<i class="fas fa-angle-double-right"></i>'
    ];
}
