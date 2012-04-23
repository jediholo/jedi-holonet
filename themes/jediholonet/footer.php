    <!-- Content footer bar -->
    <div id="contentFooter">
      <p>Powered by <a href="http://wordpress.org">Wordpress</a> //
      <a href="<?php bloginfo('rss2_url'); ?>">RSS Feed</a> - <a href="https://www.google.com/calendar/embed?src=council%40jediholo.net&ctz=America/New_York">Events Calendar</a> //
      <a href="/about/">About Us</a> - <a href="/about/legal/">Legal Notice</a> - <a href="/about/legal#privacy">Privacy Policy</a> //
      <?php wp_register('', " - \n"); ?>
      <?php wp_loginout(); echo "\n"; ?>
      </p>
      <!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
    </div>
  
  </div><!-- End of content -->

</div><!-- End of contentContainer -->

</div><!-- End of mainContainer -->

<?php wp_footer(); ?>
</body>
</html>