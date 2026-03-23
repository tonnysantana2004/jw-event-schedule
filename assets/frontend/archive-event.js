document.addEventListener('DOMContentLoaded', () => {

    flatpickr("#date-range", {
        mode: "range",
        dateFormat: "Y-m-d"
    });

    const filterForm = document.querySelector('form');

    filterForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const formData = new FormData(filterForm);

        const newUrl = new URL(window.location.origin + '/events');

        Array.from(formData.entries()).forEach(([key, value]) => {
            if (value != null && value != '') {
                newUrl.searchParams.set(key, value)
            }
        });
        window.location.href = newUrl.href;
    })
})