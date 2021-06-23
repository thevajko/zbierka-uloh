class UIHelper {

    EnableMessageSubmit(){
        document.getElementById("send-button").disabled = false;
        document.getElementById("message").disabled = false;
    }
    DisableMessageSubmit(){
        document.getElementById("send-button").disabled = true;
        document.getElementById("message").disabled = true;
    }

    ShowLoginForm(){
        document.getElementById("logout-form").classList.add("hidden");
        document.getElementById("login-form").classList.remove("hidden");
    }

    ShowLogoutForm(userName){
        document.getElementById("logout-form").classList.remove("hidden");
        document.getElementById("login-form").classList.add("hidden");
        document.getElementById("user-name").innerText = userName;
    }


}

export default  UIHelper;