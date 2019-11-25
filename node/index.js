let app = require('express')();
let http = require('http').Server(app);

http.listen(8080);

let io = require('socket.io')(http);

io.on('connection', function (socket) {
    socket.on('join', function (req) {
        let key = getChatKey(req.authUserId, req.userId);
        socket.join(`chat_${key}`);
    });

    socket.on('sendMessage', function (req) {
        let message = req.message;
        let key = getChatKey(message.from_user_id, message.to_user_id);
        io.to(`chat_${key}`).emit('receiveMessage', {message: message});
    });
});

function getChatKey (authUserId, userId) {
    return authUserId > userId ? `${authUserId}-${userId}` : `${userId}-${authUserId}`;
}
