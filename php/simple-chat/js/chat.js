
class Chat {

    constructor() {

    }
// https://stackoverflow.com/questions/6396101/pure-javascript-send-post-data-without-a-form
    GetMessages(){
        fetch("api.php?method=get-messages")
            .then(response => response.json())  // convert to json
            .then(messages => {
                let messagesHTML = "";
                messages.forEach(message => {
                    messagesHTML += `
                        <div class="message">
                            <span class="date">${message.created}</span>
                            <span>${message.message}</span>
                        </div>`;
                })
                document.getElementById("messages").innerHTML = messagesHTML;
            });
    }

}

export default Chat;