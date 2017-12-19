
//  Function to authenticate g login
function onSignIn(googleUser) {
    var profile = googleUser.getBasicProfile();
    // var id_token = googleUser.getAuthResponse().id_token;
    // console.log(id_token);
    googleSignedIn=true;
    console.log('ID: ' + profile.getId()); 
    console.log('Name: ' + profile.getName());
    console.log('Image URL: ' + profile.getImageUrl());
    console.log('Email: ' + profile.getEmail());
    var id= profile.getId();
    var name= profile.getName();
    var email= profile.getEmail();

    var q = {
        "id": id,
        "name":name,
        "email": email,
        "social_auth" :'g_auth'
        
    };
    sendData(q);
  }

     function signOut() {
     var auth2 = gapi.auth2.getAuthInstance();
     auth2.signOut().then(function () {
            console.log('User signed out.');
        });
   }
  

  // Function for fb login

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '286003621922006',
      cookie     : true,
      xfbml      : true,
      version    : 'v2.11'
    });
      
    FB.AppEvents.logPageView();   
      
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));


 function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    if (response.status === 'connected') {
        console.log('connected');
         testAPI();
    } else if (response.status === 'not_authorized') {
        console.log('Please log into this app.');
    } else {
        console.log('Please log into Facebook.');
    }
    };

    function checkLoginState() {
        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
        });
        }
        
    function testAPI() {
            //console.log('Welcome!  Fetching your information.... ');
            FB.api('/me', function(response) {
            //console.log('Successful login for: ' + response.name);
            console.log(response);
            var q={
                
                "id": response.id,
                "name":response.name,
                "social_auth" :'fb_auth'
            };
            if(response.email){
                q['email']=response.email;
            }

            sendData(q);
            
            }, {scope: 'email'});
        }

    function sendData(q) {
        
            q = "q=" + JSON.stringify(q);
        // console.log(q);
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function()
        {
           /* if (xmlhttp.readyState == 4 && xmlhttp.status == 200)*/
            /*{
                var result = JSON.parse(xmlhttp.responseText);
                // console.log(result);
                if(result["location"])
                {
                    location.href = result["location"];
                }
                $(result).each(function(index, element) {
                    showError(element["key"], element["value"]);
                });
            }*/
        };
        xmlhttp.open("POST", "views/validate_socialauth.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(q);
    }
    
    
