<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<?php astra_content_bottom(); ?>
	</div> <!-- ast-container -->
	</div><!-- #content -->
<?php
	astra_content_after();

	astra_footer_before();

	astra_footer();

	astra_footer_after();
?>
	</div><!-- #page -->
<?php
	astra_body_bottom();
	wp_footer();
?>
<?php wp_enqueue_script( 'jquery' ); ?>
<!-- Chatbot Icon -->
<div class="chatbot-icon" onclick="toggleChatbox()">
    <img src="<?php echo get_template_directory_uri(); ?>/images/chatbot.gif" alt="Chatbot">
</div>

<!-- Chatbox -->
<div class="chat-box-container" id="chat-box-container">
    <div class="chat-header">Chatbot</div>
    <div class="chat-box" id="chat-box"></div>
    <div class="input-area">
        <textarea id="user-input" placeholder="Votre message..."></textarea>
        <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<script>
    function toggleChatbox() {
        const chatbox = document.getElementById('chat-box-container');
        const chatBox = document.getElementById("chat-box");
        if (chatbox.style.display === 'none' || chatbox.style.display === '') {
            chatbox.style.display = 'block';
            if (!chatBox.dataset.welcomeMessage) {
                showWelcomeMessage();
				chatBox.dataset.welcomeMessage = "true";// Empêcher l'affichage multiple
            }
        } else {
            chatbox.style.display = 'none';
        }
    }

    function showWelcomeMessage() {
        var chatBox = document.getElementById("chat-box");
        var welcomeMessageDiv = document.createElement("div");
        welcomeMessageDiv.className = "bot-response";
        welcomeMessageDiv.innerText = "Bonjour, comment puis-je vous aider aujourd'hui ?";
        chatBox.appendChild(welcomeMessageDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function sendMessage() {
        var userMessage = document.getElementById('user-input').value;
        if (userMessage.trim() === '') return;

        var chatBox = document.getElementById("chat-box");

        var userMessageDiv = document.createElement("div");
        userMessageDiv.className = "user-message";
        userMessageDiv.innerText = userMessage;
        chatBox.appendChild(userMessageDiv);
        chatBox.scrollTop = chatBox.scrollHeight;

        var typingMessage = document.createElement("div");
        typingMessage.className = "bot-response typing";
        typingMessage.innerHTML = `<span class="dots"><span>.</span><span>.</span><span>.</span></span>`;
        chatBox.appendChild(typingMessage);
        chatBox.scrollTop = chatBox.scrollHeight;

        jQuery.ajax({
			//'https://chatbot.apps.anetat.com/chatbot/'
            url: 'http://127.0.0.1:8000/chatbot/',
            type: 'POST',
            contentType: 'application/json',
            headers: { 'X-CSRFToken': csrftoken },
            data: JSON.stringify({ 'question': userMessage }),
            success: function(response) {
                typingMessage.remove();
                var botMessageDiv = document.createElement("div");
                botMessageDiv.className = "bot-response";
                botMessageDiv.innerText = response.response;
                chatBox.appendChild(botMessageDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
            },
            error: function() {
                typingMessage.remove();
                var errorMessageDiv = document.createElement("div");
                errorMessageDiv.className = "bot-response";
                errorMessageDiv.innerText = "Désolé, une erreur s'est produite.";
                chatBox.appendChild(errorMessageDiv);
            }
        });

        document.getElementById('user-input').value = '';
    }

    function getCookie(name) {
        let cookieValue = null;
        if (document.cookie && document.cookie !== '') {
            const cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i].trim();
                if (cookie.substring(0, name.length + 1) === (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
    const csrftoken = getCookie('csrftoken');
	
	//Envoie du message en cliquant sur 'Entrer'
	document.getElementById('user-input').addEventListener('keypress', function(event) {
    if (event.key === 'Enter' && !event.shiftKey) { 
        event.preventDefault(); // Empêche le saut de ligne
        sendMessage();
    }
});

</script>

	</body>
</html>
