/**
 * Created by root on 8/20/16.
 */
var email = require("emailjs/email");

var sendMail = function (onData, ENV, to, message) {
    var serverMail = email.server.connect({
        user: ENV['MAIL_USERNAME'],
        password: ENV['MAIL_PASSWORD'],
        host: ENV['MAIL_HOST'],
        ssl: true
    });
    serverMail.send({
        text: message,
        from: "Sistema de Monitorero de red EMI",
        to: to,
        subject: "Alerta en actividad de red"
    }, function (err, message) {
        console.log('-----> Send mail, successfully');
        onData(err, message);
    });
    return this;
};

module.exports.sendMail = sendMail;