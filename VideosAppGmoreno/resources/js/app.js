import './bootstrap';

if (window.AUTH_USER_ID) {
    window.Echo.channel(`video-created-channel.${window.AUTH_USER_ID}`)
        .listen('.video.created', (e) => {
            console.log('Notificación recibida:', e);
            addNotificationToDOM(e);
        });
}

function addNotificationToDOM(data) {
    const notificationsList = document.querySelector('#notifications-list');
    if (!notificationsList) return;

    // Evitar duplicacions comprovant si la notificació ja existeix
    const existingNotification = Array.from(notificationsList.children).some(
        child => child.dataset.videoId === data.video_id.toString()
    );
    if (existingNotification) return;

    const notification = document.createElement('div');
    notification.className = 'p-4 hover:bg-slate-750 transition-colors duration-200 bg-blue-900/50 animate-pulse';
    notification.dataset.videoId = data.video_id;
    notification.innerHTML = `
        <div class="flex items-start">
            <div class="flex-shrink-0 bg-emerald-500/20 p-2 rounded-lg">
                <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
            </div>
            <div class="ml-4 flex-1">
                <div class="flex items-center justify-between">
                    <p class="font-medium text-white">${data.message}</p>
                    <span class="text-xs text-slate-400">${data.timestamp}</span>
                </div>
                <div class="mt-2 flex space-x-2">
                    <a href="/videos/${data.video_id}" class="text-sm px-3 py-1 bg-slate-700 hover:bg-slate-600 rounded-lg text-white">
                        Ver video
                    </a>
                </div>
            </div>
        </div>
    `;
    notificationsList.prepend(notification);
}
