$(function() {
    jQuery.validator.addMethod("adminUserExist", function(value, element, params) {
        console.log(element);
        return this.optional(element) || check_admin_user_existance(value, params[0]);
    }, 'Username already exist.');
})

$(function() {
    jQuery.validator.addMethod("userUsernameExist", function(value, element, params) {
        console.log(element);
        return this.optional(element) || check_user_username_existance(value, params[0]);
    }, 'Username already exist.');
})

function check_admin_user_existance(value, aid) {
    let result;
    $.ajax({
        type: 'post',
        url: base_url + "admin/admin/check_username_existance",
        async: false,
        data: {
            username: value,
            aid: aid,
        },
        dataType: "json",
        success: function(res) {
            result = res;
        }
    });
    return !result;
}

function check_user_username_existance(value, uid) {
    let result;
    $.ajax({
        type: 'post',
        url: base_url + "admin/users/check_username_existance",
        async: false,
        data: {
            username: value,
            uid: uid,
        },
        dataType: "json",
        success: function(res) {
            result = res;
        }
    });
    return !result;
}