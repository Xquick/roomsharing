window.fbAsyncInit = function () {
    FB.init({
        appId: '570643943043167',
        cookie: true,  // enable cookies to allow the server to access
        // the session
        xfbml: true,  // parse social plugins on this page
        version: 'v2.1' // use version 2.1
    });
};

(function (d) {
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    ref.parentNode.insertBefore(js, ref);
}(document));

function facebookLogin() {
    FB.login(function (response) {
        if (response.authResponse) {
            getUser(); // Get User Information.
        } else {

        }
    }, {scope: 'email'});
}

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
getUser = function () {
    FB.api('/me', function (response) {
        console.log(response);
        $.post('/login_c/process', {data: response}, function (output) {
            window.location.href = "/";
        });
    });
};
