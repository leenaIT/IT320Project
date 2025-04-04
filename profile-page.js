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
    const bookingID = button.getAttribute('data-bookingid');
    const workshopID = button.getAttribute('data-workshopid');  // Get the workshopID

    // Fetch the review data from the backend
    fetch(`get-review.php?bookingID=${bookingID}&workshopID=${workshopID}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.textContent = "My Review";
                button.setAttribute("data-reviewid", data.reviewID);
            } else {
                button.textContent = "Submit Review"; // Default state
            }
        });

    button.addEventListener('click', function() {
        const reviewID = this.getAttribute('data-reviewid') || null;

        Swal.fire({
            title: reviewID ? "Edit Your Review" : "Submit Your Review",
            html: `
                <label>Rating:</label>
                <select id="rating-${bookingID}" class="star-rating">
                    <option value="1">⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                </select>
                <textarea id="review-comment-${bookingID}" rows="4" style="width: 100%; margin-top: 10px; padding: 10px;" placeholder="Write your Review..."></textarea>
            `,
            showCancelButton: true,
            showDenyButton: reviewID !== null, // Show delete button if review exists
            denyButtonText: "Delete ",
            denyButtonColor: "#d35400",
            confirmButtonText: reviewID ? "Update Review" : "Submit Review",
            confirmButtonColor:'#f4b42b',
            cancelButtonColor:'#b0b0b0',
            cancelButtonText: "Cancel",
            didOpen: () => {
                $(`#rating-${bookingID}`).barrating({
                    theme: 'fontawesome-stars',
                });

                if (reviewID) {
                    fetch(`get-review.php?bookingID=${bookingID}&workshopID=${workshopID}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                $(`#rating-${bookingID}`).barrating('set', data.rating);
                                document.getElementById(`review-comment-${bookingID}`).value = data.comment;
                            }
                        });
                }
            },
            preConfirm: () => {
                const rating = $(`#rating-${bookingID}`).val();
                const comment = document.getElementById(`review-comment-${bookingID}`).value;

                if (!rating) {
                    Swal.showValidationMessage("Please select a rating.");
                    return false;
                }

                return { bookingID, reviewID, rating, comment, workshopID }; // Send workshopID here
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const data = result.value;
                const endpoint = data.reviewID ? 'update-review.php' : 'submit-review.php';

                fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(responseData => {
                    if (responseData.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Review Saved!",
                            text: "Your review has been successfully saved.",
                            confirmButtonColor: "#28a745"
                        });

                        // If it's a new review, update button and UI
                        if (data.reviewID === null) {  // New review
                            button.textContent = "My Review";
                            button.setAttribute("data-reviewid", responseData.reviewID);  // Set the reviewID from the response

                            // Update the review UI
                            const reviewElement = document.getElementById(`review-${bookingID}`);
                            const reviewRatingElement = reviewElement.querySelector('.review-rating');
                            const reviewCommentElement = reviewElement.querySelector('.review-comment');

                            reviewRatingElement.innerHTML = "⭐".repeat(responseData.rating);  // Update stars
                            reviewCommentElement.textContent = responseData.comment;  // Update comment
                        } else {  // Updating an existing review
                            // Update the UI directly with new data
                            const reviewElement = document.getElementById(`review-${bookingID}`);
                            const reviewRatingElement = reviewElement.querySelector('.review-rating');
                            const reviewCommentElement = reviewElement.querySelector('.review-comment');

                            reviewRatingElement.innerHTML = "⭐".repeat(responseData.rating);  // Update stars
                            reviewCommentElement.textContent = responseData.comment;  // Update comment
                        }
                    } else {
                        Swal.fire("Error!", responseData.message, "error");
                    }
                });
            } else if (result.isDenied) {
                // Handle delete review
                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d35400",
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel"
                }).then((confirmResult) => {
                    if (confirmResult.isConfirmed) {
                        fetch("delete-review.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({ reviewID })
                        })
                        .then(response => response.json())
                        .then(responseData => {
                            if (responseData.success) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Review Deleted!",
                                    text: "Your review has been removed.",
                                    confirmButtonColor: "#28a745"
                                });

                                button.textContent = "Submit Review";
                                button.removeAttribute("data-reviewid");
                            } else {
                                Swal.fire("Error!", responseData.message, "error");
                            }
                        });
                    }
                });
            }
        });
    });
});

//***************************ADD POST******************************************************
$(".add-post-btn2").click(function() {
    // Fetch user info (name and profile picture)
    $.ajax({
        url: 'get_user_info.php', // Your PHP file to fetch user info
        method: 'GET',
        success: function(response) {
            const data = JSON.parse(response); // Parse the response
            if (data.success) {
                const profilePic = data.ProfilePhoto.replace(/\\\//g, '/');
                const userName = data.name;

                Swal.fire({
                    title: 'Add New Post',
                    html: `
                        <div class="form-group">
                            <label for="images">Upload Photos:</label>
                            <div id="image-squares" class="image-squares">
                                <div class="image-square" data-index="1" id="square-1"></div>
                                <div class="image-square" data-index="2" id="square-2"></div>
                                <div class="image-square" data-index="3" id="square-3"></div>
                                <div class="image-square" data-index="4" id="square-4"></div>
                            </div>
                        </div>

                        <div class="user-info" style="margin-left:-10px; margin-top: 10px; text-align: left;">
                            <img src="${profilePic}" alt="Profile Picture" class="user-profile-pic" onError="this.onerror=null;this.src='uploads/default.png';">
                            <p style="margin-left: -20px; font-weight: bold; font-size: 17px;">${userName}</p>
                        </div>

                        <div class="form-group">
                            <label for="comment">Comment:</label>
                            <textarea id="comment" class="swal2-input" placeholder="Write your comment..."></textarea>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Post',
                    confirmButtonColor:"#f4b42b",
                    cancelButtonText: 'Cancel',
                    cancelButtonColor:"#b0b0b0",
                    preConfirm: () => {
                        const comment = document.getElementById('comment').value;
                        const images = [];
                        for (let i = 1; i <= 4; i++) {
                            const image = document.getElementById(`square-${i}`).style.backgroundImage;
                            if (image) {
                                images.push(image);
                            }
                        }
                        if (comment.trim() === '') {
                            Swal.showValidationMessage('Please write a comment');
                            return false;
                        }
                        return {
                            comment: comment,
                            images: images
                        };
                    },
                    willClose: () => {
                        $(".image-square").css('background-image', 'none');
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const { comment, images } = result.value;
                        handlePost(comment, images);
                    }
                });

                // Handle image selection and preview
              $(".image-square").click(function() {
    const index = $(this).data('index');
    const fileInput = $('<input type="file" accept="image/*" style="display: none;">');
    
    // Append to body so it works
    $('body').append(fileInput);
    fileInput.trigger('click');

    fileInput.change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $(`#square-${index}`).css('background-image', `url(${e.target.result})`);
                $(`#square-${index}`).data('file', file); // Store the file for later upload
            };
            reader.readAsDataURL(file);
        }
    });
});


            } else {
                Swal.fire('Error', 'Failed to load user info', 'error');
            }
        }
    });
});

function handlePost(comment) {
    const formData = new FormData();
    formData.append('comment', comment);

    $(".image-square").each(function(index) {
        const file = $(this).data('file'); // Retrieve the stored file
        if (file) {
            formData.append(`image${index}`, file);
        }
    });

    $.ajax({
        type: "POST",
        url: "create_post.php", // PHP script to handle post creation
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
    if (response.status === "success") {
Swal.fire({
    title: 'Post Created!',
    text: 'Your post has been added.',
    icon: 'success',
    confirmButtonText: 'Okay',
    confirmButtonColor: '#f4b42b'  // Change this color to whatever you want
});
        
         // Check if "no posts" message exists and remove it
                if ($('#posts-container').find('.post-msg').length > 0) {
                    $('#posts-container').find('.post-msg').remove(); // Remove the "no posts" message
                }
        // Dynamically add the post to the page without reloading
        const postHTML = `
            <div class="card" data-post-id="${response.postId}">
                <div class="image-carousel">
                    <div class="carousel-inner">
                        ${response.images.map((img, index) => `
                            <img src="${img}" class="post-image ${index === 0 ? 'active' : 'hidden'}" data-index="${index}">
                        `).join('')}
                    </div>
                    ${response.images.length > 1 ? `
                        <button class="prev-btn"></button>
                        <button class="next-btn"></button>
                    ` : ''}
                </div>
                <div class="card-content">
                    <div class="profile">
                        <img src="${response.userProfilePic}" class="user-circle1">
                        <p><strong>${response.userName}</strong></p>
                    </div>
                    <div class="comment-section">
                        <p class="experience-text">${comment}</p>
                    </div>
                    <div class="post-btn">
                        <button class="edit-btn2"><img src="workshops/edit-btn.png" alt="edit"></button>
                        <button class="trash-btn2"><img src="workshops/trash-btn.png" alt="delete"></button>
                    </div>
                </div>
            </div>
        `;

        $('#posts-container').prepend(postHTML);

        // Re-initialize the carousel buttons after adding the post dynamically
        if (response.images.length > 1) {
            $(postHTML).find(".prev-btn, .next-btn").show();  // Show the navigation buttons
        } else {
            $(postHTML).find(".prev-btn, .next-btn").hide();  // Hide the navigation buttons
        }

        // Add carousel navigation functionality
        $(postHTML).find('.next-btn, .prev-btn').on('click', function () {
            const carousel = $(this).closest(".image-carousel");
            const images = carousel.find(".post-image");
            let activeIndex = images.index(carousel.find(".post-image.active"));

            if ($(this).hasClass("next-btn")) {
                activeIndex = (activeIndex + 1) % images.length;
            } else {
                activeIndex = (activeIndex - 1 + images.length) % images.length;
            }

            images.removeClass("active").addClass("hidden");
            images.eq(activeIndex).removeClass("hidden").addClass("active");
        });

    } else {
        Swal.fire('Oops!', 'Something went wrong. Please try again later.', 'error');
    }
}

    });
}


// Fetch and display posts when the page loads
function loadPosts() {
    $.ajax({
        type: "GET",
        url: "get_posts.php", // Adjust this path as necessary
        success: function(response) {
            if (response.status === "success") {
                const postsContainer = $('#posts-container');
                postsContainer.empty();  // Clear any existing posts

                if (response.posts.length === 0) {
                   postsContainer.append(`
    <p class="post-msg">
        <img src="workshops/no_posts.png" alt="no post" class="no-posts-icon">
    </p>
`);
  // Display no posts message
                } else {
                    response.posts.forEach(post => {
                        const postHTML = `
  <div class="card" data-post-id="${post.postId}">                       
    <div class="image-carousel">
        <div class="carousel-inner">
            ${post.images.map((img, index) => `
                <img src="${img}" class="post-image ${index === 0 ? 'active' : 'hidden'}" data-index="${index}">
            `).join('')}
        </div>
        ${post.images.length > 1 ? `
            <button class="prev-btn"></button>
            <button class="next-btn"></button>
        ` : ''}
    </div>
    <div class="card-content">
        <div class="profile">
            <img src="${post.userProfilePic}" class="user-circle">
            <p><strong>${post.userName}</strong></p>
        </div>
     <div class="comment-section">
        <p class="experience-text">${post.comment}</p>
                        </div>
    </div>
                   <div class="post-btn">
   <button class="edit-btn2"><img src="workshops/edit-btn.png" alt="edit"> </button>
    <button class="trash-btn2"><img src="workshops/trash-btn.png" alt="delete"> </button>
                            </div>
</div>

                        `;
                        postsContainer.append(postHTML);
                        // This ensures the buttons are only shown when there are more than one image
    if (post.images.length > 1) {
        $(postHTML).find(".prev-btn, .next-btn").show(); // Show the navigation buttons
    } else {
        $(postHTML).find(".prev-btn, .next-btn").hide(); // Hide the navigation buttons
    }
                    });
                }
            } else {
                console.error('Failed to load posts. Response:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching posts. Status:', status, 'Error:', error);
            console.error('Response:', xhr.responseText);  // Check the actual response from the server
        }
    });
}

// Call the loadPosts function to load posts when the page loads
$(document).ready(function() {
    loadPosts();
});
;

$(document).on("click", ".next-btn, .prev-btn", function () {
    const carousel = $(this).closest(".image-carousel");
    const images = carousel.find(".post-image");
    let activeIndex = images.index(carousel.find(".post-image.active"));

    if ($(this).hasClass("next-btn")) {
        activeIndex = (activeIndex + 1) % images.length;
    } else {
        activeIndex = (activeIndex - 1 + images.length) % images.length;
    }

    images.removeClass("active").addClass("hidden");
    images.eq(activeIndex).removeClass("hidden").addClass("active");
});


    




    // Handle editing and deleting the post
    $(document).on('click', '.edit-btn2', function () {
        const postCard = $(this).closest('.card');
        const currentComment = postCard.find('.experience-text').text();

        // Open SweetAlert2 for editing the post
        Swal.fire({
            title: 'Edit Post',
            html: `
                <textarea id="editComment" class="swal2-textarea" placeholder="Edit your comment">${currentComment}</textarea>
            `,
            focusConfirm: false,
            preConfirm: () => {
                const updatedComment = document.getElementById('editComment').value;
                return { updatedComment };
            },
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Save Changes',
            confirmButtonColor: '#F2B42F',
            cancelButtonColor:'#b0b0b0',
        }).then((result) => {
            if (result.isConfirmed) {
                const { updatedComment } = result.value;
                postCard.find('.experience-text').text(updatedComment || 'No comment provided');

const postId = postCard.data('post-id'); 
                    $.ajax({
                    url: 'edit-post.php',
                    method: 'POST',
                    data: { postId, comment: updatedComment },
                    success: function (response) {
                        if (response === 'success') {
                          Swal.fire({
    title: 'Sucess!',
    text: 'Your post has been Updated.',
    icon: 'success',
    confirmButtonColor: '#F2B42F' // Change the "OK" button color to #F242F
});
} else {
    Swal.fire('Error', 'There was an issue deleting your post', 'error');
}
                    }
                });
            }
        });
    });

  $(document).on('click', '.trash-btn2', function () {
    const postCard = $(this).closest('.card');
    const postId = postCard.data('post-id');  // This will now correctly access postId

    console.log("postId being sent:", postId);  // Debugging line to check postId

 Swal.fire({
        title: 'Are you sure?',
        text: "Once deleted, you won't be able to recover this post!",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Yes',
        confirmButtonColor: '#F2B42F',  // Change the "OK" button color
        cancelButtonColor:'#b0b0b0',
    }).then((result) => {
        if (result.isConfirmed) {
    $.ajax({
        url: 'delete-post.php',
        method: 'POST',
        data: { postId: postId },
        success: function(response) {
            if (response === 'success') {
                 loadPosts();
                // Immediately remove the post from the UI without refreshing
                postCard.remove(); 
              Swal.fire({
    title: 'Deleted!',
    text: 'Your post has been deleted.',
    icon: 'success',
    confirmButtonColor: '#F2B42F' // Change the "OK" button color to #F242F
});
} else {
    Swal.fire('Error', 'There was an issue deleting your post', 'error');
}

        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            Swal.fire('Error', 'There was a network error.', 'error');
        }
    });
        }
});
});


//************************ FAVORITE LIST ******************************

$(document).ready(function() {
    // Fetch and display workshops with favorite status
    function loadWishlist() {
        $.ajax({
            url: 'fetch_workshops.php',
            method: 'GET',
            success: function(response) {
                console.log("Response:", response); // Log the response to the console

                const data = JSON.parse(response);
                const workshopsContainer = $('#workshops-container');
                workshopsContainer.empty();  // Clear existing workshops

                if (data.status === 'success' && data.workshops.length > 0) {
                    console.log("Workshop data:", data.workshops); // Log the workshop data

                    const wishlistHeader = `
                        <h3 id="wishlist-title" style="font-size:20px;">WISHLIST</h3>
                        <hr class="wishlist-divider"> <!-- Divider after the title -->
                    `; 
                    workshopsContainer.append(wishlistHeader);

                    data.workshops.forEach(function(workshop) {
                        console.log("Title: ", workshop.Title); // Ensure the title is there
                        const correctedURL = workshop.imageURL.replace(/\\/g, '/');
                        const heartIcon = `<img src="workshops/filled-star.png" alt="Favorite" class="wishlist-star-img">`;

                        const workshopCard = `
                            <div class="wishlist-item" data-workshop-id="${workshop.WorkshopID}">
                                <div class="wishlist-img">
                                    <img src="${correctedURL}" alt="${workshop.Title}" />
                                </div>
                                <div class="wishlist-info">
                                    <p class="workshop-name">${workshop.Title}</p>
                                    <div class="price-section">
                                        <img src="workshops/riyal.png" alt="SR" class="currency-icon">
                                        <p class="price">${workshop.Price}</p>
                                    </div>
                                    <div class="wishlist-actions">
                                        <button class="book-now">Book Now</button>
                                        <button class="wishlist-star filled">
                                            ${heartIcon}
                                            <span class="tooltip">Remove Favorite</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr class="wishlist-divider"> <!-- Divider between items -->
                        `;
                        workshopsContainer.append(workshopCard);
                    });
                } else {
                    // Show empty message if there are no workshops
                   workshopsContainer.html(`
                        <div class="empty-wishlist-message">
                            <h3>Your Wishlist is Empty</h3>
                            <p>It looks like you haven't added any workshops or Activites to your wishlist yet.</p>
                            <button class="browse-now-btn">Browse </button>
                        </div>
                    `);
                }
            }
        });
    }

    // Handle favorite button click
    $(document).on('click', '.wishlist-star', function() {
        const button = $(this); // Store reference to clicked button
        const workshopID = button.closest('.wishlist-item').data('workshop-id');

        $.ajax({
            url: 'toggle_favorite.php',
            method: 'POST',
            data: { workshopID: workshopID },
            success: function(response) {
                response = response.trim(); // Remove whitespace
                console.log("Server Response:", response); // Debugging

                if (response === 'removed') {
                    button.closest('.wishlist-item').fadeOut(300, function() { 
                        $(this).remove(); // Smooth removal
                        // Reload wishlist to check if it's empty
                        if ($('.wishlist-item').length === 0) {
                            loadWishlist();
                        }
                    });
                } else if (response !== 'added') {
                    console.error("Unexpected response:", response);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    });

    // Load wishlist when page loads
    loadWishlist();
});
