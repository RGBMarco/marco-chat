import $ from 'jquery';
import { isNull } from 'util';
//为了可控制面板
//因为设计性问题,有一部分面板可能脱离了控制
//但是会尽量保持其正确性
export class PanelManager {
    //聊天会话面板
    //用户信息面板
    //查找好友面板
    //添加好友面板
    constructor() {
        console.log("面板管理器初始化");
        this.currentPanel_ = null;
    }
    setCurrentPanel(panelId) {
        this.currentPanel_ = panelId;
        if (!isNull(this.closeCurrentPanel)) {
            $(this.currentPanel_).show();
        }
    }
    closeCurrentPanel() {
        if (!isNull(this.currentPanel_)) {
            $(this.currentPanel_).hide();
        }
        this.currentPanel_ = null;
    }
    resetCurrentPanel(panelId) {
        this.closeCurrentPanel();
        this.setCurrentPanel(panelId);
    }
}
let panel = new PanelManager();
window.panel = panel;
export {panel};
