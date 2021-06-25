import UIHelper from "./UIHelper.js";

class Chat {

    /**
     *
     * @type {UIHelper}
     */
    UI = new UIHelper();

    constructor() {

        document.getElementById("login-button").onclick = () => this.makeLogin();
        document.getElementById("logout-button").onclick = () => this.makeLogout();
        document.getElementById("send-button").onclick = () => this.postMessage();

        document.getElementById("message").onkeyup = (event) => {
            if (event.code === "Enter") {
                this.postMessage();
            }
        }
    }

    async run(){
        await this.checkLoggedState();
        setInterval(this.getMessages, 1000);
        await this.getMessages()
    }

    async checkLoggedState(){

        try {
            let response = await fetch("api.php?method=is-logged").catch(this.LogError);

            if (response.status != 200) {
                throw new Error("ERROR:" + response.status + " " + response.statusText);
            }
            let isLogged = await response.json();

            if (!isLogged) {
                throw new Error("User not logged.")
            } else {
                this.UI.enableMessageSubmit();
                this.UI.showLogoutForm(isLogged);
            }
        } catch (er) {
            this.UI.disableMessageSubmit();
            this.UI.showLoginForm();
        }

    }

    async makeLogin(){
        try {
            this.UI.showLoginLoading();
            let response = await fetch(
                "api.php?method=login",
                {
                    headers : {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    method: "POST",
                    body: "name=" +  document.getElementById("login").value
                });

            if (response.status != 200) {
                if (response.status == 455) {
                    alert("Meno '"+document.getElementById("login").value+"' už používa iný používateľ. Zadajte iné meno.")
                }
                throw new Error("ERROR:"  + response.status + " " + response.statusText);
            }
            await this.checkLoggedState();
        } catch (e) {
            console.log('Request Failed', e);
        }
    }

    async makeLogout(){
        try {
            this.UI.showLoginLoading();
            let result = await fetch("api.php?method=logout");
        } catch (err){
            console.log('Request Failed', err);
        } finally {
            await this.checkLoggedState();
        }
    }
    
    async postMessage(){

        document.getElementById("send-button").innerHTML = `<span class="loader"></span> Posielam...`;
        document.getElementById("send-button").disabled = true;
        document.getElementById("message").disabled = true;

        try {
          let response =  await fetch(
                "api.php?method=post-message",
                {
                    headers : {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    method: "POST",
                    body: "message=" +  document.getElementById("message").value
                });

            if (response.status != 200) {
                throw new Error("ERROR:"  + response.status + " " + response.statusText);
            }

            document.getElementById("message").value = "";

        } catch (err) {
            console.log('Request Failed', err);
        } finally {
            document.getElementById("send-button").innerHTML = `Odoslať`;
            document.getElementById("send-button").disabled = false;
            document.getElementById("message").disabled = false;
        }

    }
    async getMessages(){
        try {

            let response = await fetch("api.php?method=get-messages");

            if (response.status != 200) {
                throw new Error("ERROR:"  + response.status + " " + response.statusText);
            }
            let messages = await response.json();
            let messagesHTML = "";
            messages.forEach(message => {
                messagesHTML += `
                        <div class="message">
                            <span class="date">${message.created}</span>
                            <span class="user">${message.user} &gt; </span>
                            <span>${message.message}</span>
                        </div>`;
            })
            document.getElementById("messages").innerHTML = messagesHTML;
        } catch (e) {
            document.getElementById("messages").innerHTML = `<h2>Nastala chyba na strane servera.</h2><p>${e.message}</p>`;
        }
    }

}

export default Chat;