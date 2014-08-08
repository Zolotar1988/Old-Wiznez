// Messages are stored in a sender-to-sender key/val store, with the id of the lowest sender first
// and the id that's largest second.
// User 4 sending a message to id 7 will store the messages in "messages:4-7", the message from 7 to 4 will be stored there as well
exports.redis_messsagesBetweenUsersLocation = function(userId1, userId2)
{
    return 'accountConnect:messages:' + global.siteName +  ':conversations:' + (userId1 < userId2 ? userId1 + '-' + userId2 : userId2 + '-' + userId1);
};

exports.redis_messsagesNewMesssagesFromUserCount = function(userId, fromUserId)
{
    //TODO Delete the old store location
    return 'accountConnect:messages:' + global.siteName +  ':newMessagesFromUserCount:' + userId + ':' + fromUserId;
};

exports.redis_messsagesInboxQueue = function(userId)
{
    return 'accountConnect:messages:' + global.siteName +  ':inbox:' + userId;
};

exports.redis_messsagesOutboxQueue = function(userId)
{
    return 'accountConnect:messages:' + global.siteName +  ':outbox:' + userId;
};

exports.redis_messsagesnewMesssagesPendingRepliesListings = function(userId)
{
    return 'accountConnect:messages:' + global.siteName +  ':pendingReplies:' + userId;
};

exports.redis_chatAlertNotification = function(userId)
{
    return 'notifications:' + userId + ':' + global.siteName +  ':new_chatMessage';
};


exports.redis_messagesPendingArchivingLocation= function()
{
    return 'accountConnect:messagesPendingArchiving:' + global.siteName;
};




exports.store_message = function(data) {
    var message         = global.general_lib.escapeStr(data.message),
        storeLocation   = this.redis_messsagesBetweenUsersLocation(data.fromId, data.toId);

    var msg             = {};
    msg.toId            = data.toId;
    msg.fromId          = data.fromId;
    msg.message         = message;
    msg.datetime        = global.general_lib.getDateTimeTimestamp();

    global.redis.lpush(storeLocation, JSON.stringify(msg));
    global.redis.lpush(this.redis_messagesPendingArchivingLocation(), JSON.stringify(msg));

    // If the user "to" is chatting with this user, we don't set notifications
    if(global.users[data.toId] && global.users[data.toId].activeConversation == data.fromId) return true;

    global.redis.incr(this.redis_chatAlertNotification(data.toId));
    global.redis.incr(this.redis_messsagesNewMesssagesFromUserCount(data.toId, data.fromId));
};