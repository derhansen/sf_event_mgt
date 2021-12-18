/**
 * Required JavaScript for sf_event_mgt challenge/response spam detection
 */
document.addEventListener('DOMContentLoaded', function () {
    var crElement = document.getElementById('js-cr-challenge');
    if (typeof crElement === 'undefined' || crElement === null) {
        return;
    }
    var challenge = crElement.getAttribute('data-challenge');
    console.log('foo');

    // ROT13 the challenge - source: https://stackoverflow.com/a/617685/1744743
    challenge = challenge.replace(/[a-zA-Z]/g, function (c) {
        return String.fromCharCode((c <= 'Z' ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
    });

    crElement.value = challenge;
});
