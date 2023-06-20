const conn = new WebSocket('ws://127.0.0.1:8282/');

conn.onopen = async (e) => {
    console.log("Connection established!");

    if (window.location.pathname == "/chat") {

        const responseGet = await fetch(`/chat/getusers`);
        const responseGetData = await responseGet.json();

        const liUsers = document.getElementsByClassName('chat-room-ul')[0].getElementsByClassName('chat-room-ul-li');
        
        Object.keys(responseGetData).forEach( (elem) => {
            let flag = false;
            Array.from(liUsers).forEach( (element) => {
                if(element.textContent == responseGetData[elem]["name"]) {
                    flag = true;
                }
            });
            if (flag == false) {
                const ulUsers = document.getElementsByClassName('chat-room-ul')[0];

                const createLi = document.createElement('li');
                createLi.setAttribute('class', 'chat-room-ul-li');
                createLi.textContent = responseGetData[elem]["name"];

                ulUsers.appendChild(createLi);
                
            }
        });

    }

    if (/\/chat\/[0-9]+/.test(window.location.pathname)) {

        const idRoom = window.location.pathname.match(/[0-9]+/)[0];
        const userName = document.querySelector('input[name=userName]');

        conn.send(JSON.stringify({
            idRoom: idRoom,
            userName: userName.value
        }));
    }
};

conn.onmessage = (e) => {

    if (window.location.pathname == '/chat') {

        const data = JSON.parse(e.data);
        const chatUsersOnlineBox = document.getElementsByClassName('chat-room-ul')[0];
        const liUsersOnline = chatUsersOnlineBox.getElementsByClassName('chat-room-ul-li');
        
        Array.from(liUsersOnline).forEach( (elem) => {
            if (elem.textContent.replace(/\([0-9]+\)/, '').trim() == data.userName) {
                elem.textContent = data.userName + ` (${data.idRoom})`;
            }
        });
    }

    if (/\/chat\/[0-9]+/.test(window.location.pathname)) {

        const dataObj = JSON.parse(e.data);

        if (dataObj.inputMessage !== undefined) {
    
            const idRoomSender = dataObj.idRoom;
            const idRoomReceiver = window.location.pathname.match(/[0-9]+/)[0];

            if (idRoomSender === idRoomReceiver) {

                const chatRoomBox = document.getElementsByClassName('chat-room-message')[0];

                const hrElem = document.createElement('hr');
                hrElem.setAttribute('class', 'chat-line-message');

                const spanElemWrap = document.createElement('span');
                spanElemWrap.setAttribute('class', 'chat-span-message-wrap');

                const spanElem = document.createElement('span');
                spanElem.setAttribute('class', 'chat-span-message');
                spanElem.textContent = dataObj.inputMessage;

                const spanElemUser = document.createElement('span');
                spanElemUser.setAttribute('class', 'chat-span-user');
                spanElemUser.textContent = dataObj.userName + ":";

                const inputConversationCode = document.createElement('input');
                inputConversationCode.setAttribute('class', 'conversation-code');
                inputConversationCode.setAttribute('type', 'hidden');
                inputConversationCode.setAttribute('name', 'conversation-code');
                inputConversationCode.setAttribute('id', 'conversation-code');
                inputConversationCode.value = dataObj.conversationCode;

                spanElemWrap.appendChild(spanElemUser);
                spanElemWrap.appendChild(spanElem);
                spanElemWrap.appendChild(inputConversationCode);

                chatRoomBox.appendChild(hrElem);
                chatRoomBox.appendChild(spanElemWrap);
            }
        }

        roomManagement.scrollToBotRoom();
    }

};

conn.onerror = (e) => {
    console.log("Websocket error: ", e);
};