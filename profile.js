function toggleVisibility() {
    // Get the new post container and the first card
    const newPostContainer = document.querySelector('.new-post-container');
    const firstCard = document.querySelector('.first-card');

    // Toggle visibility
    if (newPostContainer.style.display === 'none') {
        newPostContainer.style.display = 'block'; // Show the new post form
        firstCard.style.display = 'none'; // Hide the first card
    } else {
        newPostContainer.style.display = 'none'; // Hide the new post form
        firstCard.style.display = 'block'; // Show the first card
    }
}
