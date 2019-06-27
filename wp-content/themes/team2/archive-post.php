<?php
/**
 * Template Name: Archive
 */
    $home_url = get_home_url();
    
    
    $footerMenu = [];

    foreach (wp_get_nav_menu_items('footer') as $key => $value) {
        $footerMenu[] = [
            "title" => $value->title,
            "url" => $value->url
        ];
    }

    $posts_query = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => -1
    ]);
    
    $all_posts = [];
    
    if ($posts_query->have_posts()) {
        while ($posts_query->have_posts()) {
            $posts_query->the_post();
            $all_posts[] = [
                'title' => get_the_title(),
                'thumbnail_url' => get_the_post_thumbnail_url(get_the_ID(), 'list-thumbnail'),
                'excerpt' => get_the_excerpt(),
                'permalink' => get_the_permalink()
            ];
        }
        wp_reset_postdata();
    }
?>


<?php get_header() ?>

<div id="app">
    <Archive
        all-posts-string="<?= htmlspecialchars(json_encode($all_posts), ENT_QUOTES, 'UTF-8') ?>"
        footer-menu="<?= htmlspecialchars(json_encode($footerMenu), ENT_QUOTES, 'UTF-8') ?>"
        home-url="<?= $home_url ?>"
    >
    </Archive>
</div>

<?php get_footer() ?>