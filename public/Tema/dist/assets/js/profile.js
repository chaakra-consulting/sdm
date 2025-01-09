(function () {
    'use strict';

    function loadFile(event) {
        const reader = new FileReader();
        const file = event.target.files[0];

        if (file && file.type.match('image.*')) {
            reader.onload = function () {
                const output = document.querySelectorAll('.profile-img'); // Selecting all elements with class
                output.forEach(function(element) {
                    element.src = reader.result;
                });
            };
            reader.readAsDataURL(file);
        } else {
            event.target.value = '';
            alert('Please select a valid image.');
        }
    }

    // Selecting all input elements with the class
    const ProfileChanges = document.querySelectorAll('.profile-change');
    if (ProfileChanges.length > 0) { // Ensuring at least one element is found
        ProfileChanges.forEach(function(element) {
            element.addEventListener('change', loadFile);
        });
    } else {
        console.error('No element with class "profile-change" found.');
    }
})();