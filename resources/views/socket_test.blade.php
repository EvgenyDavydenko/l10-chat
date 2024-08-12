<!DOCTYPE html>
<html>
<head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.4.0/socket.io.slim.js"></script>
</head>
<body>
    <p>hello laravel echo</p>
<script type="module">

import Echo from "https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.js"
import axios from 'https://cdn.jsdelivr.net/npm/axios@1.6.2/+esm'

let gqlUrl = 'http://localhost/graphql';
let wsHost = 'localhost:6001';

class WS {
    id = 2;
    token = '1|nyw6pDoVMMJng4QZgqUFxYiGVsrcWdPXj1CN0HyQfb3cc7af';
    constructor() {
        this.echo = new Echo({
            broadcaster: 'socket.io',
            host: wsHost,
            bearerToken: this.token,
            transports: ['websocket'],
        });
        this.echo.connector.socket
            .on('connect',()=>this.onSocketConnected())
        }
    onSocketConnected() {
        this.echo.private('dialog.' + this.id).on('messageSent', (d)=>console.log(d));
        
    }
}
new WS;

</script>
<div>

</div>
</body>
</html>
