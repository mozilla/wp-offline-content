if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register($swUrl, {
    scope: $swScope
  })
  .then(() => console.log('sw registered'))
  .catch(() => console.error('error while registering the sw'));
}
