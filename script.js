document.addEventListener("DOMContentLoaded", () => {
    const forms = document.querySelectorAll(".forma-js");

    forms.forEach(form => {
    form.addEventListener("submit", e=> {
        e.preventDefault();

        let valid=true;

        const fullName=document.getElementById("fullName");
        const phone=document.getElementById("phone");
        const address=document.getElementById("address");
        const email=document.getElementById("email");
        const reason=document.getElementById("reason");

        const nameRegex= /^[A-Za-z\s]+$/;
        if (!fullName.value.trim() || !nameRegex.test(fullName.value.trim())) {
            valid = false;
            fullName.style.border = "2px solid red";
        } else {
            fullName.style.border = "1px solid #ccc";
        }

        const phoneRegex = /^[0-9]+$/;
        if (!phone.value.trim() || !phoneRegex.test(phone.value.trim())) {
            valid = false;
            phone.style.border = "2px solid red";
        } else {
            phone.style.border = "1px solid #ccc";
        }

        if (!address.value.trim()) {
            valid = false;
            address.style.border = "2px solid red";
        } else {
            address.style.border = "1px solid #ccc";
        }

        const emailRegex = /^\S+@\S+\.\S+$/;
        if (!email.value.trim() || !emailRegex.test(email.value.trim())) {
            valid = false;
            email.style.border = "2px solid red";
        } else {
            email.style.border = "1px solid #ccc";
        }

        if(valid){
            const redirectPage=form.dataset.redirect;
            if(redirectPage){
            window.location.href=redirectPage;
            }
        } else{
            alert("Please fill in all fields correctly!");
        }
        });
    });
    });
