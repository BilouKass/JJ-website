self.addEventListener("install", (event) => {
    self.skipWaiting(); 
});
self.addEventListener('push', (event) => {
    const data = event.data ? event.data.json() : {};
    console.log(data);
    event.waitUntil(
    self.registration.showNotification(data.title, {
        body : data.message,
        data : data
    }));

})

self.addEventListener("notificationclick", (event) => {
    let url = event.notification.data.url;
    event.notification.close();
    event.waitUntil(
        self.clients.openWindow(url));
  });