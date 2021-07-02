import Chat from "./chat.js";

window.onload = async function (){
    window.chat = new Chat();
    await window.chat.run();
}