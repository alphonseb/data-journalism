<?php
    $title = get_the_title();
    
    $excerpt = get_the_excerpt();
    
    // $thumbnail_url = get_the_post_thumbnail_url();
    
    $vignettes = [];
    if (have_rows('vignettes')) {
        while (have_rows('vignettes')) {
            the_row();
            $vignettes[]=[
                'type' => get_row_layout(),
                'front' => get_sub_field('front'),
                'back' => get_sub_field('back')
            ];
        }
    }
?>

<?php get_header() ?> 

<div id="app">
    <Single 
    title="<?= $title ?>"
    excerpt="<?= $excerpt ?>"
    thumbnail_url="<?= $thumbnail_url ?>"
    vignettes_string="<?= htmlspecialchars(json_encode($vignettes), ENT_QUOTES, 'UTF-8') ?>"
    >
    </Single>
</div>

<?php get_footer() ?> 