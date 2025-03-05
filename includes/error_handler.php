<?php
function displayError($message) {
    echo '<div class="error-message">';
    echo '<img src="' . BASE_URL . '/install/assets/images/error.svg" alt="Error">';
    echo '<p>' . htmlspecialchars($message) . '</p>';
    echo '</div>';
}

function displaySuccess($message) {
    echo '<div class="success-message">';
    echo '<img src="' . BASE_URL . '/install/assets/images/success.svg" alt="Success">';
    echo '<p>' . htmlspecialchars($message) . '</p>';
    echo '</div>';
} 