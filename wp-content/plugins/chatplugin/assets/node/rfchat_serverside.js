var io             = require('socket.io').listen(8080),
    rdis           = require('redis'),
    user_lib       = require('./libraries/user');


// Set global variables here
global.redis        = rdis.createClient();
global.siteName     = 'decate';
global.general_lib  = require('./libraries/general');
global.users        = {};

io.set('log level', 1);

process.on('uncaughtException', function (err) {
    console.log('Caught exception: ' + err);
});


io.on('connection', function(socket) {

    //================================================================================================================================================================
    // User functions/events
    //================================================================================================================================================================

    socket.on('set_userData', function(userId) {
        user_lib.userData(userId, function(response) {
            response                            = JSON.parse(response);
            socket.user                         = {};
            socket.user.id                      = response.id;
            socket.user.avatar                  = response.thumb;
            socket.user.username                = response.username;

            global.users[response.id]           = socket.user;

            socket.join('user_channel' + socket.user.id);
        });
    });


    socket.on('send_message', function(data) {
        if(!data.message) return false;
//        if(typeof global.users[data.toId] == 'undefined') return false;

        var fromData    = socket.user;
        data.fromId     = socket.user.id;
        socket.broadcast.to('user_channel' + data.toId).emit('receive_message', { from:socket.user, message:(data.message) });
    });





    socket.on('clear_tabBarNotifications', function(data) {
        socket.broadcast.to('user_channel' + socket.user.id).emit('clear_tabBarNotifications');
    });











    //================================================================================================================================================================
    // Server side functions/events
    //================================================================================================================================================================
    socket.on('photo_liked', function(data) {
        socket.broadcast.emit('photo_liked', data);
    });

    socket.on('user_pulse', function(data) {
        socket.broadcast.emit('user_pulse', data);
    });

    socket.on('admin_user_activity', function(data) {
//        socket.broadcast.emit('admin_user_activity', data);
    });


});