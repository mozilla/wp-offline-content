<div class="wrap">
  <h1><?php _e('Offline', 'offline'); ?></h1>
  <form method="post" action="options.php">
    <?php settings_fields(self::$options_group); ?>
    <?php do_settings_sections(self::$options_page_id); ?>
    <?php submit_button(__('Save Changes', 'offline'), 'primary'); ?>
  </form>
</div>
