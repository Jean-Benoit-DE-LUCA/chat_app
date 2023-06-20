const roomManagement = {

    init: function() {

        roomManagement.formDeleteSubmit();
        roomManagement.formLoadSubmit();
        roomManagement.formSaveSubmit();
        roomManagement.formSubmit();

        roomManagement.scrollToBotRoom();
    },

    displayStatus: document.getElementsByClassName('display-status')[0],
    displayStatusMessage: document.getElementsByClassName('display-status-message')[0],

    formElement: document.getElementsByClassName('chat-room-form')[0],

    formElementSave: document.getElementsByClassName('save-form')[0],
    formElementLoad: document.getElementsByClassName('load-form')[0],
    formElementDelete: document.getElementsByClassName('delete-form')[0],

    buttonSend: document.getElementsByClassName('chat-room-send-message-submit')[0],
    buttonSave: document.getElementsByClassName('save-conversation-submit')[0],

    selectDelete: document.getElementsByClassName('delete-conversation')[0],
    selectLoad: document.getElementsByClassName('load-conversation')[0],

    inputMessage: document.getElementsByClassName('chat-room-send-message')[0],
    inputToken: document.querySelector('input[name=_token]'),
    inputUserId: document.getElementsByClassName('userid-send')[0],
    inputHiddenUserName: document.getElementsByClassName('username-send')[0],

    inputHiddenConversationCode: document.getElementsByClassName('conversation-code'),

    inputSaveNumber: document.getElementsByClassName('save-conversation')[0],

    chatRoomBox: document.getElementsByClassName('chat-room-message')[0],

    formSubmit: function() {

        if (roomManagement.formElement !== undefined) {

            roomManagement.formElement.addEventListener('submit', roomManagement.handleFormSubmit);
        }
    },

    formSaveSubmit: function() {

        if (roomManagement.formElementSave !== undefined) {

            roomManagement.formElementSave.addEventListener('submit', roomManagement.handleFormSaveSubmit);
        }
    },

    formLoadSubmit: function() {

        if (roomManagement.formElementLoad !== undefined) {

            roomManagement.formElementLoad.addEventListener('submit', roomManagement.handleFormLoadSubmit);
        }
    },

    formDeleteSubmit: function() {

        if (roomManagement.formElementDelete !== undefined) {

            roomManagement.formElementDelete.addEventListener('submit', roomManagement.handleFormDeleteSubmit);
        }
    },

    handleFormSubmit: async function(e) {

        e.preventDefault();
        const idRoom = window.location.href.split('/').slice(-1)[0];
        const inputHiddenConversationCodeForm = document.getElementsByClassName('chat-room-form')[0].getElementsByClassName('conversation-code')[0];

        const response = await fetch(`/chat/${idRoom}`, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json',
                'X-CSRF-Token': roomManagement.inputToken.value
            },
            body: JSON.stringify({
                inputMessage: roomManagement.inputMessage.value,
                idRoom: idRoom,
                userId: roomManagement.inputUserId.value,
                conversationCode: inputHiddenConversationCodeForm.value,
                chatRoomSendMessageSubmit: roomManagement.buttonSend.name
            })
        });
        
        if (response.ok) {

            roomManagement.createMessage(roomManagement.inputMessage.value, roomManagement.inputHiddenUserName.value, inputHiddenConversationCodeForm.value);

            conn.send(JSON.stringify({
                inputMessage: roomManagement.inputMessage.value,
                idRoom: idRoom,
                userId: roomManagement.inputUserId.value,
                userName: roomManagement.inputHiddenUserName.value,
                conversationCode: inputHiddenConversationCodeForm.value,
            }));

            roomManagement.inputMessage.value = '';
            roomManagement.inputMessage.focus();
        }

        roomManagement.chatRoomBox.scrollTop = roomManagement.chatRoomBox.scrollHeight;
    },

    handleFormSaveSubmit: async function(e) {

        e.preventDefault();
        const idRoom = window.location.href.split('/').slice(-1)[0];
        const userId = document.getElementsByClassName('userid-send')[0];
        const inputHiddenConversationCodeChat = document.getElementsByClassName('chat-room-message')[0].getElementsByClassName('conversation-code');

        const uniqueConversationsCode = [];
        Array.from(inputHiddenConversationCodeChat).forEach( (elem) => {
            if (!uniqueConversationsCode.find( element => element == elem.value)) {
                uniqueConversationsCode.push(elem.value);
            }
        });

        if (uniqueConversationsCode.length !== 0) {

            const response = await fetch(`/chat/${idRoom}`, {
                method: 'POST',
                headers: {
                    'Content-type': 'application/json',
                    'X-CSRF-Token': roomManagement.inputToken.value
                },
                body: JSON.stringify({
                    userId: userId.value,
                    saveConversation: roomManagement.inputSaveNumber.value,
                    uniqueConversationsCode: uniqueConversationsCode,
                    saveConversationSubmit: roomManagement.buttonSave.name,
                })
            });

            roomManagement.displayStatus.classList.add('active');
            roomManagement.displayStatusMessage.textContent = "Saved!";

            setTimeout(() => {
                roomManagement.displayStatus.classList.remove('active');
            }, 1800);

            const optionElemDelete = document.createElement('option');
            optionElemDelete.value = roomManagement.inputSaveNumber.value;
            optionElemDelete.textContent = roomManagement.inputSaveNumber.value;

            const optionElemLoad = document.createElement('option');
            optionElemLoad.value = roomManagement.inputSaveNumber.value;
            optionElemLoad.textContent = roomManagement.inputSaveNumber.value;
            
            roomManagement.selectDelete.appendChild(optionElemDelete);
            roomManagement.selectLoad.appendChild(optionElemLoad);

            //

            const keepSelectedValue = roomManagement.inputSaveNumber.value;

            roomManagement.inputSaveNumber.selectedIndex = roomManagement.inputSaveNumber.length -1;

            Array.from(roomManagement.inputSaveNumber).forEach( (elem) => {

                if (elem.value == keepSelectedValue) {
                    elem.remove();
                }
            });

            
            //
        }
    },

    handleFormLoadSubmit: async function(e) {

        e.preventDefault();
        const idRoom = window.location.href.split('/').slice(-1)[0];
        const loadNumber = document.getElementsByClassName('load-conversation')[0].value;

        const response = await fetch(`/chat/${idRoom}/load/${loadNumber}`);
        const data = await response.json();

        roomManagement.displayStatus.classList.add('active');
        roomManagement.displayStatusMessage.textContent = "Loaded!";

        setTimeout(() => {
            roomManagement.displayStatus.classList.remove('active');
        }, 1800);

        dataKeysObj = Object.keys(data);

        dataKeysObj.forEach((key) => {
            roomManagement.createMessage(data[key]["message"], data[key]["username"], data[key]["conversation_code"]);
        });

        roomManagement.scrollToBotRoom();
    },

    handleFormDeleteSubmit: async function(e) {

        e.preventDefault();
        const idRoom = window.location.href.split('/').slice(-1)[0];
        const deleteNumber = document.getElementsByClassName('delete-conversation')[0].value;

        const response = await fetch(`/chat/${idRoom}/delete/${deleteNumber}`, {
            method: "DELETE",
            headers: {
                "Content-type": "application/json",
                "X-CSRF-Token": roomManagement.inputToken.value
            }
        });

        roomManagement.displayStatus.classList.add('active');
        roomManagement.displayStatusMessage.textContent = "Deleted!";

        setTimeout(() => {
            roomManagement.displayStatus.classList.remove('active');
        }, 1800);

        Array.from(roomManagement.selectDelete).forEach( (elem) => {

            if (elem.value == deleteNumber) {
                elem.remove()
            }
        });

        Array.from(roomManagement.selectLoad).forEach( (elem) => {

            if (elem.value == deleteNumber) {
                elem.remove()
            }
        });

        const optionElem = document.createElement('option');
        optionElem.setAttribute('value', deleteNumber);
        optionElem.textContent = deleteNumber;

        roomManagement.inputSaveNumber.appendChild(optionElem);
    },

    scrollToBotRoom: function() {

        if (roomManagement.chatRoomBox !== undefined) {

            roomManagement.chatRoomBox.scrollTop = roomManagement.chatRoomBox.scrollHeight;
        }
    },

    createMessage: function(message, username, conversationCode) {

        const hrElem = document.createElement('hr');
        hrElem.setAttribute('class', 'chat-line-message');

        const spanElemWrap = document.createElement('span');
        spanElemWrap.setAttribute('class', 'chat-span-message-wrap');

        const spanElem = document.createElement('span');
        spanElem.setAttribute('class', 'chat-span-message');
        spanElem.textContent = message;

        const spanElemUser = document.createElement('span');
        spanElemUser.setAttribute('class', 'chat-span-user');
        spanElemUser.textContent = username + ":";

        const inputConversationCode = document.createElement('input');
        inputConversationCode.setAttribute('class', 'conversation-code');
        inputConversationCode.setAttribute('type', 'hidden');
        inputConversationCode.setAttribute('name', 'conversation-code');
        inputConversationCode.setAttribute('id', 'conversation-code');
        inputConversationCode.value = conversationCode;

        spanElemWrap.appendChild(spanElemUser);
        spanElemWrap.appendChild(spanElem);
        spanElemWrap.appendChild(inputConversationCode);

        roomManagement.chatRoomBox.appendChild(hrElem);
        roomManagement.chatRoomBox.appendChild(spanElemWrap);
    },
};