"use strict";
const app = Vue.createApp({
    data() {
        return {
            showProspectEntryPage: true,
            showProspectQuesPage:false,
            showSuccessSavedPage:false,
       
        };
    },
    methods:{
        get_currentPage(){
         
        },
    }    
})
const root= app.mount('#app')