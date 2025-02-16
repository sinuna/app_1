document.addEventListener('DOMContentLoaded', function () {
    const taskApp = Vue.createApp({
        data() {
            return {
                isClient: true
            };
        }
    });

    taskApp.mount('#task-app');
});
