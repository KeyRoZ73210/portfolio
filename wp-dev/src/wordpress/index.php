<?php get_header(); ?>

<main class="site-main">
  <?php while (have_posts()) : the_post(); ?>

    <?php the_content(); ?>

  <?php endwhile; ?>

  <?php include __DIR__.'/modules-content.php'; ?>
</main>

<?php get_footer() ?>
