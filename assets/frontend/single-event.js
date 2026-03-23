
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#attendance-form');
    const cancelForm = document.querySelector('#cancel-attendance-form');

    form?.addEventListener('submit', (e) => {
        e.preventDefault();
        formData = new FormData(form);

        wp.apiFetch({
            path: '/jwes/v1/attendance',
            method: 'POST',
            data: {
                user_id: formData.get('user_id'),
                post_id: formData.get('post_id')
            },
        }).then(() => {
            // window.location.reload();
        });

    })

    cancelForm?.addEventListener('submit', (e) => {

        e.preventDefault();
        formData = new FormData(cancelForm);

        wp.apiFetch({
            path: '/jwes/v1/attendance',
            method: 'DELETE',
            data: {
                user_id: formData.get('user_id'),
                post_id: formData.get('post_id')
            },
        }).then(() => {
            // window.location.reload();
        });

    })
})

