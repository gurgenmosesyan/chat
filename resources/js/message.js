import io from 'socket.io-client';

function Message() {
    this.form = $('#message-form');
}

Message.prototype.init = function () {
    if (typeof nodeHost === 'undefined') {
        return false;
    }

    this.initSocket();
    this.listenFormSubmit();
    this.listenReceiveMessage();
};

Message.prototype.initSocket = function () {
    this.socket = io(nodeHost);

    this.socket.emit('join', {
        authUserId: authUserId,
        userId: userId
    });
};

Message.prototype.listenFormSubmit = function () {
    this.form.on('submit', (e) => {
        e.preventDefault();

        let submitBtn = this.form.find(':submit');
        if (submitBtn.attr('disabled')) {
            return false;
        }
        submitBtn.attr('disabled', true);

        $.ajax({
            url: this.form.attr('action'),
            type: this.form.attr('method'),
            data: this.form.serializeArray(),
            dataType: 'json',
            success: (result) => {
                submitBtn.attr('disabled', false);
                if (result.status === 'OK') {
                    this.socket.emit('sendMessage', {message: result.message});
                    this.form.find('textarea').val('');
                } else {
                    alert('Error!');
                }
            },
            error: () => {
                alert('Error!');
            }
        });
    });
};

Message.prototype.listenReceiveMessage = function () {
    this.socket.on('receiveMessage', (result) => {
        let message = result.message;
        let date = this.getDateString(new Date(message.created_at));

        let html = `<p class="message">
                    ${message.message}
                    <br>
                    <span>${date}</span>
                </p>`;

        $('#messages').prepend(html);
    });
};

Message.prototype.getDateString = function (date) {
    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear();
    let hours = date.getHours();
    let minutes = date.getMinutes();

    month = month < 10 ? '0' + month : month;
    day = day < 10 ? '0' + day : day;
    hours = hours < 10 ? '0' + hours : hours;
    minutes = minutes < 10 ? '0' + minutes : minutes;

    return `${day}.${month}.${year} ${hours}:${minutes}`;
};

let message = new Message();
message.init();
