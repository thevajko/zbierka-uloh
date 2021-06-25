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

        document.getElementById("message").onkeyup = async (event) => {
            if (event.code === "Enter") {
                await this.postMessage();
            }
        }

        document.getElementById("cancel-private").onclick = () => this.UI.removePrivate();

    }

    async run(){
        await this.checkLoggedState();
        setInterval(this.getMessages, 1000);
        setInterval(this.getUsers, 1000);
        await this.getMessages()
    }

    async checkLoggedState(){

        try {
            let response = await fetch("api.php?method=is-logged");

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
            this.UI.disableMessageSubmit(false);
            this.UI.showLoginForm();
        }

    }

    async makeLogin(){
        try {
            this.UI.showStatusBarLoading();
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
            this.UI.showStatusBarLoading();
            let result = await fetch("api.php?method=logout");
        } catch (err){
            console.log('Request Failed', err);
        } finally {
            await this.checkLoggedState();
        }
    }
    
    async postMessage(){

        this.UI.disableMessageSubmit();
        try {

          let pEle = document.getElementById("private");
          let priv = ( pEle.innerText == "" ? "" : '&private=' + pEle.innerText );

          let response =  await fetch(
                "api.php?method=post-message",
                {
                    headers : {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    method: "POST",
                    body: "message=" +  document.getElementById("message").value + priv
                });

            if (response.status != 200) {
                throw new Error("ERROR:"  + response.status + " " + response.statusText);
            }

            document.getElementById("message").value = "";
        } catch (err) {
            console.log('Request Failed', err);
        } finally {
            this.UI.enableMessageSubmit();
            document.getElementById("message").focus();
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
                let p = message.private_for != null ? "private" : "";
                let userNames =  message.private_for != null ? `${message.user} > ${message.private_for}` : message.user;
                messagesHTML += `
                        <div class="message ${p}">
                            <span class="date">${message.created}</span>
                            <span class="user">${userNames} : </span>
                            <span>${message.message}</span>
                        </div>`;
            })
            document.getElementById("messages").innerHTML = messagesHTML;
        } catch (e) {
            document.getElementById("messages").innerHTML = `<h2>Nastala chyba na strane servera.</h2><p>${e.message}</p>`;
        }
    }

    async getUsers(){
        try {

            let response = await fetch("api.php?method=users");

            if (response.status != 200) {
                throw new Error("ERROR:"  + response.status + " " + response.statusText);
            }

            let users = await response.json();


            let userList = document.getElementById("users-list");
            userList.innerHTML = "";
            users.forEach(user => {
                let btn = document.createElement("button");
                btn.innerText = user.name;
                btn.onclick = () => window.chat.UI.addPrivate(user.name);
                userList.append(btn);
            })

        } catch (e) {
            document.getElementById("users-list").innerHTML = "";
           // console.log('Request Failed', e);
        }
    }

}

export default Chat;