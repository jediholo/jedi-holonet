    <!-- Sidebar -->
    <div class="sidebar" id="sidebar-<?php echo get_root_name(); ?>">
<?php for ($i = 1; $i <= $GLOBALS['JEDI_config']['numSidebars']; $i++) : ?>
<?php if (is_active_sidebar("sidebar-$i")) : ?>

      <!-- Sidebar <?php echo $i; ?> -->
      <div class="box" id="sidebar-<?php echo $i; ?>">
        <ul>

<?php if (!dynamic_sidebar("sidebar-$i")) : ?>
         <li>Static Sidebar #<?php echo $i; ?>!</li>
<?php endif; ?>

        </ul>
      </div>
<?php endif; ?>
<?php endfor; ?>
    
    </div>
