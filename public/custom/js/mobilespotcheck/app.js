"use strict";
const app = Vue.createApp({
    data() {
        return {
            showSelectCarersPage: true,
            showSelectServiceUsersPage:false,
            showDisplaySelectedInfoPage :false,
            showSpotCheckPage:false,
            showSuccessSavedPage:false,
            showReportModalPage:false,
            selectedCarer:'',
            selectedServiceUser: '',
        
             
        };
    },
    methods:{
        get_currentPage(){
            const myArray = [];
            if (this.showSelectCarersPage) {
                myArray.push(1);
            }
            if (this.showSelectServiceUsersPage) {
                myArray.push(2);
            }
            if (this.showSpotCheckPage) {
                myArray.push(3);
            }
        
            return myArray[0];
        },
    }    
})
const root= app.mount('#app')
