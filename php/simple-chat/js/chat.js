
class Chat {

    constructor() {

    }
// https://stackoverflow.com/questions/6396101/pure-javascript-send-post-data-without-a-form
    GetMessages(){
        fetch("api.php?action=get-messages")
            .then( result => {
                JSON.parse(result);
            })
    }

}

export default Chat;