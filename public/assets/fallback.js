
// Immediately check if page was restored from bfcache
if (window.performance && window.performance.navigation.type === 2) {
    document.write('<style>body{visibility:hidden !important;}</style>');
    window.location.reload();
}

// Fallback for older browsers
window.onpageshow = function (event) {
    if (event.persisted) {
        document.body.style.visibility = 'hidden';
        window.location.reload();
    }
};