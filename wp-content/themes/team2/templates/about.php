<?php 
$id = get_the_id();
$post = get_post($id);
$content = $post->post_content;

$args = [
    'post_type' => 'post',
    'posts_per_page' => -1
];
$query = new WP_Query($args);

$posts = [];

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $posts[] = [
            'id' => get_the_id(),
            'title' => get_the_title()
        ];
    }
    wp_reset_postdata();
}



$data = [
    'title' => get_the_title(),
    'content' => $content,
    'posts' => $posts
];
// echo '<pre>';
// print_r(json_encode($props));
// echo '</pre>';
?>
<div id="app">
    <about data='<?= json_encode($data) ?>'> </about>
    <h3>lol</h3>
</div>