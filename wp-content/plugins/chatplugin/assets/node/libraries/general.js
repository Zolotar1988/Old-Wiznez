exports.escapeStr = function(str) {
    return String(str) .replace(/&/g, '&amp;') .replace(/"/g, '&quot;') .replace(/'/g, '&#39;') .replace(/</g, '&lt;') .replace(/>/g, '&gt;');
};

exports.getDateTimeTimestamp = function() {
    var date        = new Date().getTime();
    var postedDate  = new Date(date);
    var year        = postedDate.getFullYear();
    var month       = postedDate.getMonth();
    var day         = postedDate.getDate();
    var hour        = postedDate.getHours();
    var mins        = postedDate.getMinutes();
    var secs        = postedDate.getSeconds();

    return year + '-' + month + '-' + day + ' ' + hour + ':' + mins + ':' + secs;
};