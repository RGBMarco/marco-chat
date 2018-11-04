import $ from 'jquery';

export default class ChatPanel {
    constructor(messageSwitchIdOne,contactSwitchIdOne,mineSwitchIdOne,messageSwitchIdTwo,contactSwitchIdTwo,mineSwitchIdTwo,messagePanelId,contactPanelId,minePanelId) {
        this.messageSwitchIdOne_ = messageSwitchIdOne;
        this.contactSwitchIdOne_ = contactSwitchIdOne;
        this.mineSwitchIdOne_ = mineSwitchIdOne;
        this.messageSwitchIdTwo_ = messageSwitchIdTwo;
        this.contactSwitchIdTwo_  = contactSwitchIdTwo;
        this.mineSwitchIdTwo_ = mineSwitchIdTwo;
        this.messagePanelId_ = messagePanelId;
        this.contactPanelId_ = contactPanelId;
        this.minePanelId_ = minePanelId;
        $(this.messageSwitchIdOne_).on('click',{that:this},this.switchToMessagePanel);
        $(this.contactSwitchIdOne_).on('click',{that:this},this.switchToContactPanel);
        $(this.mineSwitchIdOne_).on('click',{that:this},this.switchToMinePanel);
        $(this.messageSwitchIdTwo_).on('click',{that:this},this.switchToMessagePanel);
        $(this.contactSwitchIdTwo_).on('click',{that:this},this.switchToContactPanel);
        $(this.mineSwitchIdTwo_).on('click',{that:this},this.switchToMinePanel);
    }
    switchToMessagePanel(event) {
        let that = event.data.that;
        console.log(that);
        $(that.messagePanelId_).show();
        $(that.contactPanelId_).hide();
        $(that.minePanelId_).hide();
    }
    switchToContactPanel(event) {
        let that = event.data.that;
        console.log("switch");
        console.log(that);
        console.log($(that.messagePanelId_).height());
        $(that.messagePanelId_).hide();
        $(that.contactPanelId_).show();
        $(that.minePanelId_).hide();
    }
    switchToMinePanel(event) {
        let that = event.data.that;
        $(that.messagePanelId_).hide();
        $(that.contactPanelId_).hide();
        $(that.minePanelId_).show();
    }
}
$(document).ready(function(){
    let chatPanel = new ChatPanel('#switchMessage_1','#switchContact_1','#switchMine_1','#switchMessage_2','#switchContact_2','#switchMine_2','#message-panel','#contacts-card-panel','#mine-panel');
    $('.contacts-group-user').on('click',function() {
        $($(this).siblings('.collapse').get(0)).toggle();
    });
});