let websocket = new WebSocket("ws://localhost:666");
websocket.onmessage = function(event) {
    postMessage(event.data);
}
this.onmessage = function(event) {
    websocket.send(event.data);
}