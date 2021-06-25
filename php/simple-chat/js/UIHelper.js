class UIHelper {

    enableMessageSubmit(){
        document.getElementById("send-button").innerHTML = `Odoslať`;
        document.getElementById("send-button").disabled = false;
        document.getElementById("message").disabled = false;
    }

    disableMessageSubmit(showLoading = true){
        let sendB = document.getElementById("send-button");
        if (showLoading) {
            sendB.innerHTML = `<span class="loader"></span> Posielam...`;
        }
        sendB.disabled = true;
        document.getElementById("message").value = "";
        document.getElementById("message").disabled = true;
    }

    showLoginLoading(){
        let loader = document.createElement("div");
        loader.classList.add("loader");
        document.getElementById("logout-form").classList.add("hidden");
        document.getElementById("login-form").classList.add("hidden");
        document.getElementById("status-bar").append(loader);
    }

    showLoginForm(){
        document.getElementById("logout-form").classList.add("hidden");
        document.getElementById("login-form").classList.remove("hidden");
        document.querySelector("#status-bar > .loader")?.remove();
    }

    showLogoutForm(userName){
        document.getElementById("logout-form").classList.remove("hidden");
        document.getElementById("login-form").classList.add("hidden");
        document.getElementById("user-name").innerText = userName;
        document.querySelector("#status-bar > .loader")?.remove();
    }

}

export default  UIHelper;