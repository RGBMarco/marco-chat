import $ from 'jquery';

export default class ChatPanel {
    constructor(messagePanelId,contactPanelId,minePanelId) {
        this.messagePanelId_ = messagePanelId;
        this.contactPanelId_ = contactPanelId;
        this.minePanelId_ = minePanelId;
    }
}