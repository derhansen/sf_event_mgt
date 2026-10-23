/**
 * Prevents multiple submissions of the sf_event_mgt registration form (e.g. by double-clicking the submit button)
 */
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form[name="registration"]');
    if (form === null) {
        return;
    }
    var submitted = false;

    form.addEventListener('submit', function (event) {
        if (submitted) {
            event.preventDefault();
            return;
        }
        submitted = true;

        // Disable submit buttons after the form data has been collected, so a possible submitter value is sent
        setTimeout(function () {
            form.querySelectorAll('[type="submit"]').forEach(function (button) {
                button.disabled = true;
            });
        }, 0);
    });

    // Re-enable the form when the page is restored from the browser back/forward cache
    window.addEventListener('pageshow', function (event) {
        if (!event.persisted) {
            return;
        }
        submitted = false;
        form.querySelectorAll('[type="submit"]').forEach(function (button) {
            button.disabled = false;
        });
    });
});
