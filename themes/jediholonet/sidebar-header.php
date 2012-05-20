<?php if (is_active_sidebar('sidebar-header')) : ?>

      <!-- Sidebar Header -->
      <div class="sidebar box" id="sidebar-header">
        <ul>

<?php if (!dynamic_sidebar('sidebar-header')) : ?>
         <li>Static Sidebar Header</li>
<?php endif; ?>

        </ul>
      </div>

<?php endif; ?>
