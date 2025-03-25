    let isMouseDown = false;
let startX;
let scrollLeft;

const cardsContainer = document.querySelector('.experience-cards');


    cardsContainer.addEventListener('mousedown', (e) => {
  isMouseDown = true;
  startX = e.pageX - cardsContainer.offsetLeft;
  scrollLeft = cardsContainer.scrollLeft;
  cardsContainer.style.cursor = 'grabbing';
});

cardsContainer.addEventListener('mouseleave', () => {
  isMouseDown = false;
  cardsContainer.style.cursor = 'grab';
});

cardsContainer.addEventListener('mouseup', () => {
  isMouseDown = false;
  cardsContainer.style.cursor = 'grab';
});

cardsContainer.addEventListener('mousemove', (e) => {
  if (!isMouseDown) return; // Only move if mouse is down
  e.preventDefault();
  const x = e.pageX - cardsContainer.offsetLeft;
  const walk = (x - startX) * 2; // Adjust scrolling speed
  cardsContainer.scrollLeft = scrollLeft - walk;
});



const wishlistItemsContainer = document.querySelector('.wishlist-items'); // Select the wishlist-items container

wishlistItemsContainer.addEventListener('mousedown', (e) => {
  isMouseDown = true;
  startX = e.pageX - wishlistItemsContainer.offsetLeft;
  scrollLeft = wishlistItemsContainer.scrollLeft;
  wishlistItemsContainer.style.cursor = 'grabbing'; // Change cursor to grabbing
});

wishlistItemsContainer.addEventListener('mouseleave', () => {
  isMouseDown = false;
  wishlistItemsContainer.style.cursor = 'grab'; // Change cursor to grab when mouse leaves
});

wishlistItemsContainer.addEventListener('mouseup', () => {
  isMouseDown = false;
  wishlistItemsContainer.style.cursor = 'grab'; // Change cursor to grab when mouse is released
});

wishlistItemsContainer.addEventListener('mousemove', (e) => {
  if (!isMouseDown) return; // Only move if mouse is down
  e.preventDefault();
  const x = e.pageX - wishlistItemsContainer.offsetLeft;
  const walk = (x - startX) * 2; // Adjust scrolling speed (increase multiplier for faster scroll)
  
});

// Event listener to handle loading images
window.addEventListener('load', () => {
    document.querySelectorAll('.wishlist-img').forEach((img) => {
        img.classList.add('loaded');
    });
});


    document.addEventListener("DOMContentLoaded", function() {
        // Get the modal and button elements
        const modal = document.getElementById("editProfileModal");
        const btn = document.getElementById("editButton");
        const span = document.querySelector(".close");

        // Ensure elements exist before adding event listeners
        if (modal && btn && span) {
            // Show the modal when the Edit button is clicked
            btn.addEventListener("click", function(event) {
                event.preventDefault(); // Prevent any default action if it's a form
                modal.style.display = "block"; // Show the modal
            });

            // Close the modal when the close button (X) is clicked
            span.onclick = function() {
                modal.style.display = "none"; // Hide the modal
            }

            // Close the modal if the user clicks outside of it
            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = "none"; // Hide the modal if clicked outside
                }
            }
        }
    });
    
    // *******booking modal******
    
    // Get modal and button elements
const modal1 = document.getElementById("bookingModal");
const modalContent1 = document.querySelector(".modal-content1");
const openModalButton = document.getElementById("openBookingModal");
const closeModalButton = document.querySelector(".close1");

// Open modal
openModalButton.onclick = function () {
  modal1.style.display = "flex";
};

// Close modal when clicking the close button
closeModalButton.onclick = function () {
  modal1.style.display = "none";
};

// Close modal when clicking outside of modal-content1
modal1.onclick = function (event) {
  if (!modalContent1.contains(event.target)) {
    modal1.style.display = "none";
  }
};


// Function to open a specific tab
function openTab(evt, tabName) {
  var i, tabcontent, tablinks;

  // Hide all tab contents
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Remove "active" class from all tab links
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the selected tab content
  document.getElementById(tabName).style.display = "block";

  // Add "active" class to the clicked tab
  evt.currentTarget.className += " active";
}

// Ensure that the tabs are working after the page has fully loaded
window.onload = function() {
  // Default open the "All" tab
  document.getElementsByClassName("tablinks")[0].click();
};


function deleteBooking(bookingID) {
    Swal.fire({
        title: "Are you sure?",
        text: "This booking will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#f4b42b",
        cancelButtonColor: "#b0b0b0",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with deletion
            fetch("delete-booking.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ BookingID: bookingID })
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
    icon: data.success ? "success" : "error",
    title: data.success ? "Deleted!" : "Error!",
    text: data.message,
    confirmButtonColor: data.success ? "#28a745" : "#d33"  // Green for success, Red for error
});


                if (data.success) {
                    // Remove the booking item from the page
                    const bookingElement = document.getElementById(`${bookingID}`);
                    if (bookingElement) {
                        bookingElement.remove();
                    }
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: "error",
                    title: "Oops!",
                    text: "Something went wrong. Try again."
                });
            });
        }
    });
}

//edit *********
document.querySelectorAll(".edit-booking").forEach(button => {
    button.addEventListener("click", function () {
        let bookingID = this.dataset.id; // Get the booking ID

        Swal.fire({
            title: '<img src="workshops/avaT.png" style="width: 100px; transform: scale(4.5); display: block; margin: 0 auto;"> <br> <span style="font-size: 20px; font-weight: bold; font-family: Roboto,serif;">️Edit Booking Date & Time</span>',
            html: '<input type="text" id="dateTimePicker" class="swal2-input" style="font-size:15px;" placeholder="Select Date & Time">',
            didOpen: () => {
                flatpickr("#dateTimePicker", {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    minDate: "today",
                    time_24hr: true
                });
            },
            showCancelButton: true,
            confirmButtonColor: "#f4b42b",
            cancelButtonColor: "#b0b0b0",
            confirmButtonText: "Update",
            cancelButtonText: "Cancel",
            
            preConfirm: () => {
                let selectedDateTime = document.getElementById("dateTimePicker").value;
                let selectedDate = new Date(selectedDateTime);
                let now = new Date();

                if (!selectedDateTime) {
                    Swal.showValidationMessage("⚠ Please select a date & time.");
                    return false;
                }

                if (selectedDate < now) {
                    Swal.showValidationMessage("⚠ Cannot select a past date & time.");
                    return false;
                }

                return selectedDateTime;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                let newDateTime = result.value;

console.log("Sending data:", { BookingID: bookingID, BookingDateTime: newDateTime });

                // Send updated date & time to backend
                fetch("edit-booking.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ BookingID: bookingID, BookingDateTime: newDateTime }) // Fixed key name
                })
                .then(response => response.json())
                .then(data => {
                     console.log("Server response:", data); // Log server response
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Updated!",
                            text: "Your booking date & time has been updated.",
                            confirmButtonColor: "#28a745" // Green for success
                        });

                        // Update the booking date in the UI dynamically
                        document.getElementById(bookingID).querySelector(".booking-date").innerText = newDateTime;
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: data.message, 
                            confirmButtonColor: "#d33" // Red for error
                        });
                    }
                })
                .catch(() => {
                    console.error("Fetch error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Something went wrong. Try again later.",
                        confirmButtonColor: "#d33"
                    });
                });
            }
        });
    });
});


//***************************************review *************************************

document.querySelectorAll('.review-btn').forEach(button => {
    button.addEventListener('click', function() {
        const bookingID = this.getAttribute('data-bookingid');
        const workshopID = this.getAttribute('data-workshopid');

        Swal.fire({
            title: 'Submit Your Review',
            html: `
                <div class="star-rating" id="star-rating-${bookingID}">
                    <span class="star" data-rating="1">&#9733;</span>
                    <span class="star" data-rating="2">&#9733;</span>
                    <span class="star" data-rating="3">&#9733;</span>
                    <span class="star" data-rating="4">&#9733;</span>
                    <span class="star" data-rating="5">&#9733;</span>
                </div>
                <textarea id="review-comment-${bookingID}" rows="4" placeholder="Write your review here..." style="width: 100%;"></textarea>
            `,
            showCancelButton: true,
            confirmButtonText: 'Submit Review',
            cancelButtonText: 'Cancel',
            confirmButtonColor:'#f4b42b',
            cancelButtonColor:'#b0b0b0',
            
            didOpen: () => {
                // Attach event listeners inside the pop-up
                document.querySelectorAll(`#star-rating-${bookingID} .star`).forEach(star => {
                    star.addEventListener('click', function() {
                        const rating = this.getAttribute('data-rating');
                        document.querySelectorAll(`#star-rating-${bookingID} .star`).forEach(s => {
                            s.classList.toggle('selected', s.getAttribute('data-rating') <= rating);
                        });
                    });
                });
            },
            preConfirm: async () => {
                const selectedStar = document.querySelector(`#star-rating-${bookingID} .star.selected`);
                const rating = selectedStar ? selectedStar.getAttribute('data-rating') : null;
                const comment = document.getElementById(`review-comment-${bookingID}`).value;

                if (!rating) {
                    Swal.showValidationMessage('Please provide a rating.');
                    return false;
                }

                const reviewData = { workshopID: workshopID, rating: rating, comment: comment || '', bookingID: bookingID };
console.log('Sending data:', JSON.stringify(reviewData)); // Add this line to see what data is being sent

                // Ensure the fetch request completes before closing the alert
                try {
                    const response = await fetch('submit-review.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(reviewData)
                    });

                    const data = await response.json();

                    console.log('Response from server:', data); // Log response for debugging

                    if (!data.success) {
                        throw new Error(data.message);
                    }

                    return data; // Returning data allows it to be used in `.then()`
                } catch (error) {
                    Swal.showValidationMessage(`Error: ${error.message}`);
                    return false;
                }
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const data = result.value;
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: "Your review has been submitted successfully.",
                    confirmButtonColor: "#28a745"
                });

                // Update UI after successful submission
                document.getElementById(`review-btn-${data.bookingID}`).style.display = 'none';
                document.getElementById(`review-section-${data.bookingID}`).innerHTML = `
                    <div class="review-content">
                        <p>Rating: ${data.rating}</p>
                        <p>Comment: ${data.comment || 'No comment provided'}</p>
                        <button class="edit-review-btn" data-bookingid="${data.bookingID}">Edit Review</button>
                        <button class="delete-review-btn" data-bookingid="${data.bookingID}">Delete Review</button>
                    </div>
                `;
            }
        });
    });
});




// Handle edit review
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('edit-review-btn')) {
        const bookingID = e.target.getAttribute('data-bookingid');

        Swal.fire({
            title: 'Edit Your Review',
            html: `
                <div class="star-rating" id="star-rating-edit-${bookingID}">
                    <span class="star" data-rating="1">&#9733;</span>
                    <span class="star" data-rating="2">&#9733;</span>
                    <span class="star" data-rating="3">&#9733;</span>
                    <span class="star" data-rating="4">&#9733;</span>
                    <span class="star" data-rating="5">&#9733;</span>
                </div>
                <textarea id="review-comment-edit-${bookingID}" rows="4" placeholder="Edit your review here..." style="width: 100%;"></textarea>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update Review',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const rating = document.querySelector(`#star-rating-edit-${bookingID} .star.selected`)?.getAttribute('data-rating');
                const comment = document.getElementById(`review-comment-edit-${bookingID}`).value;

                if (!rating) {
                    Swal.showValidationMessage('Please provide a rating.');
                    return false;
                }

                return { bookingID: bookingID, rating: rating, comment: comment || '' };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('update-review.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(result.value)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Updated!",
                            text: "Your review has been updated successfully.",
                            confirmButtonColor: "#28a745"
                        });

                        document.getElementById(`review-section-${data.bookingID}`).innerHTML = `
                            <div class="review-content">
                                <p>Rating: ${data.rating}</p>
                                <p>Comment: ${data.comment || 'No comment provided'}</p>
                                <button class="edit-review-btn" data-bookingid="${data.bookingID}">Edit Review</button>
                                <button class="delete-review-btn" data-bookingid="${data.bookingID}">Delete Review</button>
                            </div>
                        `;
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                });
            }
        });
    }

    // Handle delete review
    if (e.target.classList.contains('delete-review-btn')) {
        const bookingID = e.target.getAttribute('data-bookingid');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to recover this review!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('delete-review.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ bookingID: bookingID })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            text: "Your review has been deleted successfully.",
                            confirmButtonColor: "#d33"
                        });

                        document.getElementById(`review-section-${bookingID}`).innerHTML = '';
                        document.getElementById(`review-btn-${bookingID}`).style.display = 'inline-block';
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                });
            }
        });
    }
});
