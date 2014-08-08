exports.redis_userBlockListLocation = function(userId, userId2)
{
    return 'accountConnect:users:userBlocking:' + userId + ':' + userId2;
};

exports.checkIfUser1CanInteractWithUser2 = function(userId1, userId2, callback)
{
    global.redis.get(this.redis_userBlockListLocation(userId1, userId2), function(err, response) {
        callback(response);
    });
};





// Because JS is asynchronous, this function needs to receive the callback of what's to be done with the userID
exports.userIdFromKey = function(userKey, callback) {
    global.redis.get('chat:' + global.siteName + ':userKeysRef:' + userKey, function(err, response) {
        callback(response);
    });
};

// Here we get the user data from Redis
exports.userData = function(userId, callback) {
    global.redis.get('rfchat:users:userData:' + userId, function(err, response) {
        callback(response);
    });
};