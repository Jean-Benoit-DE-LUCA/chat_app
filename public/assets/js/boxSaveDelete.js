const boxSaveDelete = {
    
    init: function() {
        
        boxSaveDelete.toggleSaveDeleteBox();
    },

    saveDeleteBox: document.getElementsByClassName("chat-room-save-delete-box")[0],

    toggleSaveDeleteBox: function() {

        if (boxSaveDelete.saveDeleteBox !== undefined) {

            boxSaveDelete.saveDeleteBox.addEventListener("click", boxSaveDelete.handleToggleSaveDeleteBox);
        }
    },

    handleToggleSaveDeleteBox: function(e) {

        boxSaveDelete.saveDeleteBox.classList.toggle('active');

    }
};