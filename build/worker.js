let websocket = new WebSocket("ws://localhost:13000");
websocket.onopen = function(event) {
    
};
websocket.onmessage = function(event) {
    let msg = event.data;
    postMessage(msg);
};
websocket.onclose = function(event) {
    postMessage("close!");
};
self.onmessage = function(event) {
    postMessage("onmessage!");
    websocket.send(event.data);
};

function isBaseRequest(request) {
    return !(typeof(request.reuqest) === undefined) && !(typeof(request.data) === undefined);
}
function handleInitMessage(request) {
    websocket.send(JSON.stringify(request));
}