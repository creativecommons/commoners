<div class="post-meta-data">
    <p class="entry-date">
        <span class="light-text"><?php echo render::date_format( get_the_date() ) ?></span>
    </p>
    <p class="entry-categories">
        <?php echo get_the_category_list( ', ', '', get_the_ID() ); ?>
    </p>
    <p class="entry-tags">
        <?php echo get_the_tag_list('<span class="strong-tiny">Tags:</span> ', ' ', '', get_the_ID() );  ?>
    </p>
</div>