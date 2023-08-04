async function registerServiceWorker() {
    const registration = await navigator.serviceWorker.register("assets/js/sw.js");
    let subscription = await registration.pushManager.getSubscription();
  
    if (!subscription) {
      console.log("No subscription registered");
      subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: "BEqwV-CRXpCnhJM_NhHydvj5-9O8a59KYbmx0LHgP8msIhEVJlgJulY3UyMB_zJB_vFSJqWWEerll4SR2PWX40w",
      });
      await saveSubscription(subscription);
    }

    return
  }

/**
 * @param {PushSubscription} subscription
 * @returns {Promise<void>}
 */
 async function saveSubscription(subscription) {
  await fetch("/school/ressources/push.php", {
    method: "post",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify(subscription.toJSON())
  })
}
