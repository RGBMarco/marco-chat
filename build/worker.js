let websocket = new WebSocket("ws://localhost:13000");
websocket.onopen = function(event) {
    requestInit();
};
websocket.onmessage = function(event) {
    requestDebug("接收到网络信息!");
    postMessage(event.data);
};
websocket.onclose = function(event) {
    requestDebug("网络连接已经被关闭!");
};
self.onmessage = function(event) {
    let request = JSON.parse(event.data);
    if (!isBaseRequest(request)) {
        return;
    }
    switch (String(request.request)) {
        case "init":
            handleInitMessage(request);
        break;
        case "message":
            handleSenderMessage(request);
        break;
        default:
        return;
    }     
};

function isBaseRequest(request) {
    return !(typeof(request.reuqest) === undefined) && !(typeof(request.data) === undefined);
}
function handleInitMessage(request) {
    websocket.send(JSON.stringify(request));
    requestDebug("已发送初始化消息!");
}

function requestInit() {
    let request = {
        request:"init",
        data:{

        }
    };
    postMessage(JSON.stringify(request));
}
function requestDebug(message) {
    let request = {
        request:"debug",
        data: {
            message:message
        }
    };
    postMessage(JSON.stringify(request));
}

function handleSenderMessage(request) {
    websocket.send(JSON.stringify(request));
    requestDebug("已发送发送者消息!");
}