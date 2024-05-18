<header class="entry-header">
<?php
rizhuti_v2_entry_title(
    array(
        'link' => false,
        'tag'  => 'h1',
    )
);
rizhuti_v2_entry_meta(
    array('category' => true,
        'author'         => _cao('is_single_meta_author', 1),
        'views'          => _cao('is_single_meta_views', 1),
        'favnum'         => _cao('is_single_meta_favnum', 1),
        'date'           => _cao('is_single_meta_date', 1),
        'edit'           => true)
);
?>
</header>
