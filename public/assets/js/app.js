const app = {

    init: function() {
        
        boxSaveDelete.init();
        roomManagement.init();
    },
    
};

window.addEventListener('DOMContentLoaded', app.init);