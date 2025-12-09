
    function validatelogin() {
         
         var email = document.getElementById("email").value.trim();
         var password = document.getElementById("password").value.trim();

         if(email=="" || password==""){
            alert("All fields are required!");
            return false;
         }

        var emailRegex=/^[\w.-]+@[\w.-]+\.\w{2,}$/;
        if (!emailRegex.test(email)) {
        alert("Invalid email address!");
        return false;
    }

    if(password.length<6){
        alert("Password must be at least 6 characters!");
        return false;
    }

    return true;
        
    }