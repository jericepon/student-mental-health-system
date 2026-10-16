// assets/js/script.js
document.addEventListener("DOMContentLoaded", function () {
    console.log("Student Mental Health System Frontend Initialized.");
    
    // Simple verification feedback on form submission
    const forms = document.querySelectorAll("form");
    forms.forEach(form => {
        form.addEventListener("submit", function () {
            console.log("Form submission in progress...");
        });
    });
});