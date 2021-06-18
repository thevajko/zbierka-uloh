
class Chat {

    constructor() {
        setInterval(this.GetMessages, 1000);
        this.GetMessages();

        document.getElementById("send-button").onclick = () => {
            this.PostMessage();
        }

        document.getElementById("message").onkeyup = (event) => {
            if (event.code === "Enter") {
                this.PostMessage();
            }
        }
    }

    PostMessage(){
        fetch(
            "api.php?method=post-message",
            {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                method: "POST",
                body: "message=" +  document.getElementById("message").value
            })
            .then(response => {
                if (response.status != 200) {
                    throw new Error("ERROR:"  + response.status + " " + response.statusText);
                }
            })
            .then( () => {
                document.getElementById("message").value = "";
            })
            .catch(err => console.log('Request Failed', err));
    }

// https://stackoverflow.com/questions/6396101/pure-javascript-send-post-data-without-a-form
    GetMessages(){
        fetch("api.php?method=get-messages")
            .then(response => {
                if (response.status != 200) {
                    throw new Error("ERROR:"  + response.status + " " + response.statusText);
                }
                return response.json()
            })  // convert to json
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
            })
            .catch(err => console.log('Request Failed', err));
        ;
    }

}

export default Chat;