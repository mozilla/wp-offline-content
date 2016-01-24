if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register(<?php echo "'$sw_url'"; ?>, {
    scope: <?php echo "'$sw_scope'"; ?>
  })
  .then(() => console.log('sw registered'))
  .catch(() => console.error('error while registering the sw'));
}
