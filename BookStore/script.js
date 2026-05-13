let locationbtn=document.getElementById("location");
let popup = document.querySelector(".locationframe");
let closebtn = document.getElementById("locationclose");


locationbtn.addEventListener("click", function () {
    popup.style.display = "block";
});

closebtn.addEventListener("click", function () {
    popup.style.display = "none";
});