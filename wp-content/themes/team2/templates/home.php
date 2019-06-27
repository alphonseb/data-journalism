<?php

$home_url = get_home_url();
$archive_url = get_permalink(get_page_by_title('archive'));

$all_posts =  [];
$args = [
    'post_type' => 'post',
    'posts_per_page' => -1
];
$posts_query = new WP_Query($args);

if ($posts_query->have_posts()) {
    while ($posts_query->have_posts()) {
        $posts_query->the_post();
        $all_posts[] = [
            'title' => get_the_title(),
            'incent' => get_field('incent'),
            'thumbnail_url' => get_the_post_thumbnail_url(get_the_ID(), 'preview-thumbnail'),
            'permalink' => get_the_permalink(),
            'transport_type' => get_the_terms(get_the_ID(), 'category')[0]->slug
        ];
    }
    wp_reset_postdata();
}

// function csv_to_json($file)
// {
//     $csv = file_get_contents(THEME_PATH . '/data/' . $file);
//     $csvLines = explode("\n", $csv);
//     $csvLines = array_map("utf8_encode", $csvLines);
//     $indexes = str_getcsv(array_shift($csvLines));
//     $array = array_map(
//         function ($e) use ($indexes) {
//             $csvArray = str_getcsv($e);
//             if (count($csvArray) == count($indexes)) {
//                 return array_combine($indexes, $csvArray);
//             }
//         },
//         $csvLines
//     );
//     array_pop($array);
//     file_put_contents(THEME_PATH . '/data/' . str_replace('.csv','.json',$file), json_encode($array));
//     return json_encode($array);
// }

$covoit_json = file_get_contents(THEME_PATH . '/data/covoiturage.json');
$elec_json = file_get_contents(THEME_PATH . '/data/electrique.json');
$garesFrance_json = file_get_contents(THEME_PATH . '/data/gares-france.json');

$veloLibres_json = file_get_contents(THEME_PATH . '/data/velo-libre-service.json');
$parkingVelo_json = file_get_contents(THEME_PATH . '/data/parking-velo.json');
$garesIDF_json = file_get_contents(THEME_PATH . '/data/gares-idf.json');



$forward_posts = [];
foreach (get_field('forward_posts') as $post_key => $post) {
    setup_postdata($post);
    $forward_posts[$post_key] = [];
    foreach ($post as $key => $value) {
        $forward_posts[$post_key][$key] = $value;
    }
    $forward_posts[$post_key]['post_thumbnail_url'] = get_the_post_thumbnail_url(get_the_ID(), 'list-thumbnail');
    $forward_posts[$post_key]['post_url'] = get_the_permalink();
}
wp_reset_postdata();

$footerMenu = [];

foreach (wp_get_nav_menu_items('footer') as $key => $value) {
    $footerMenu[] = [
        "title" => $value->title,
        "url" => $value->url
    ];
}



?>
<div class="loader">
    <p>Bienvenue sur <strong>Feuille de Route</strong></p>
    <p>Veuillez patienter pendant le chargement de notre outil révolutionnaire</p>
    <img src="<?= THEME_URL ?>/public/loader.gif" alt="">
</div>
<div id="app">
    <Home 
        gares-idf="<?= htmlspecialchars($garesIDF_json, ENT_QUOTES, 'UTF-8') ?>" 
        parking-velo="<?= htmlspecialchars($parkingVelo_json, ENT_QUOTES, 'UTF-8') ?>" 
        velo-libres="<?= htmlspecialchars($veloLibres_json, ENT_QUOTES, 'UTF-8') ?>" 
        covoit="<?= htmlspecialchars($covoit_json, ENT_QUOTES, 'UTF-8') ?>" 
        elec="<?= htmlspecialchars($elec_json, ENT_QUOTES, 'UTF-8') ?>" 
        gares-france="<?= htmlspecialchars($garesFrance_json, ENT_QUOTES, 'UTF-8') ?>" 
        forward-posts-string="<?= htmlspecialchars(json_encode($forward_posts), ENT_QUOTES, 'UTF-8') ?>" 
        all-posts-string="<?= htmlspecialchars(json_encode($all_posts), ENT_QUOTES, 'UTF-8') ?>" 
        footer-menu="<?= htmlspecialchars(json_encode($footerMenu), ENT_QUOTES, 'UTF-8') ?>" 
        home-url="<?= $home_url ?>"
        archive-url="<?= $archive_url ?>"
    >
    </Home>
</div>