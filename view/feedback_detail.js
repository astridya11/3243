document.addEventListener('DOMContentLoaded', function() {
    // Handle Like/Dislike button clicks
    document.querySelectorAll('.like-btn, .dislike-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            handleReaction(this, 'feedback');
        });
    });

    document.querySelectorAll('.like-reply-btn, .dislike-reply-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            handleReaction(this, 'reply');
        });
    });

    // Handle Edit Reply button clicks
    document.querySelectorAll('.edit-reply-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const replyID = this.getAttribute('data-reply-id');
            const replyContent = this.getAttribute('data-reply-content');
            const feedbackID = this.getAttribute('data-feedback-id');
            showEditReplyForm(replyID, replyContent, feedbackID);
        });
    });

    // Handle Edit Feedback button clicks
    document.querySelectorAll('.edit-feedback-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const feedbackID = this.getAttribute('data-feedback-id');
            const feedbackTitle = this.getAttribute('data-feedback-title');
            const feedbackContent = this.getAttribute('data-feedback-content');
            showEditFeedbackForm(feedbackID, feedbackTitle, feedbackContent);
        });
    });

    // Modal close event
    document.querySelectorAll('.close, .close-feedback').forEach(function(span) {
        span.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });

    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    });
});

function toggleReplyForm(replyID) {
    const form = document.getElementById(`reply-form-${replyID}`);
    if (form) {
        // Toggle the display property between 'none' and 'block'
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
    } else {
        console.error(`Reply form with ID reply-form-${replyID} not found.`);
    }
}


function handleReaction(button, itemType) {
    const itemID = button.getAttribute('data-item-id');
    const reaction = button.getAttribute('data-reaction');
    const userID = button.getAttribute('data-user-id');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', "../controller/update_reaction.php", true); 
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        const response = JSON.parse(xhr.responseText);

        if (response.status === 'success') {
            const likeElement = document.querySelector(`[data-item-id="${itemID}"][data-reaction="like"]`);
            const dislikeElement = document.querySelector(`[data-item-id="${itemID}"][data-reaction="dislike"]`);

            if (itemType === 'feedback') {
                if (likeElement) {
                    likeElement.innerHTML = `Like ${response.feedbackLike}`;
                    likeElement.setAttribute('data-feedback-like', response.feedbackLike);
                }
                if (dislikeElement) {
                    dislikeElement.innerHTML = `Dislike ${response.feedbackDislike}`;
                    dislikeElement.setAttribute('data-feedback-dislike', response.feedbackDislike);
                }
            } else if (itemType === 'reply') {
                if (likeElement) {
                    likeElement.innerHTML = `Like ${response.replyLike}`;
                    likeElement.setAttribute('data-reply-like', response.replyLike);
                }
                if (dislikeElement) {
                    dislikeElement.innerHTML = `Dislike ${response.replyDislike}`;
                    dislikeElement.setAttribute('data-reply-dislike', response.replyDislike);
                }
            }
        } else {
            alert('Error: ' + response.message);
        }
    };

    xhr.send(`itemID=${encodeURIComponent(itemID)}&reaction=${encodeURIComponent(reaction)}&itemType=${encodeURIComponent(itemType)}&userID=${encodeURIComponent(userID)}`);
}

function showEditReplyForm(replyID, replyContent, feedbackID) {
    document.getElementById('edit-reply-id').value = replyID;
    document.getElementById('edit-feedback-id').value = feedbackID;
    document.getElementById('edit-reply-content').value = replyContent;
    document.getElementById('edit-modal').style.display = 'block';
}

function showEditFeedbackForm(feedbackID, feedbackTitle, feedbackContent) {
    document.getElementById('edit-feedback-id').value = feedbackID;
    document.getElementById('edit-feedback-title').value = feedbackTitle;
    document.getElementById('edit-feedback-content').value = feedbackContent;
    document.getElementById('edit-feedback-modal').style.display = 'block';

}

function confirmDelete(replyID) {
    if (confirm('Are you sure you want to delete this reply?')) {
        window.location.href = `../controller/delete_reply.php?replyID=${encodeURIComponent(replyID)}`;
    }
}

function confirmDeleteFeedback(feedbackID) {
    if (confirm('Are you sure you want to delete this feedback?')) {
        window.location.href = `../controller/delete_feedback.php?feedbackID=${encodeURIComponent(feedbackID)}`;
    }
}