
let locationbtn = document.getElementById("location");
let popup = document.querySelector(".locationframe");
let closebtn = document.getElementById("locationclose");

locationbtn.addEventListener("click", function () {
    popup.style.display = "block";
});

closebtn.addEventListener("click", function () {
    popup.style.display = "none";
});


function addToCart(book_id)
{
    let formData = new FormData();
    formData.append("book_id", book_id);
    formData.append("quantity", 1);
    fetch("../cart/add_to_cart.php",{
        method:"POST",
        body:formData
    })
    .then(response=>response.json())
    .then(data=>{
        alert(data.message);
    })
    .catch(()=>{
        alert("Error");
    });
}

function updateCart(cart_id,change)
{
    let formData = new FormData();
    formData.append("cart_id",cart_id);
    formData.append("change",change);
    fetch("../cart/update_cart.php",{
        method:"POST",
        body:formData
    })
    .then(response=>response.json())
    .then(data=>{
        location.reload();
    });
}

function removeCart(cart_id)
{
    let formData = new FormData();
    formData.append("cart_id",cart_id);
    fetch("../cart/remove_cart.php",{
        method:"POST",
        body:formData
    })
    .then(response=>response.json())
    .then(data=>{
        location.reload();
    });
}

function searchBooks()
{
    let q = document.getElementById("searchBox").value;
    let filter = document.getElementById("filter").value;

    let path = window.location.pathname;

    let url = "";
    let detailsPath = "";

if (path.includes("book_details.php") || path.includes("book_list.php"))
{
    // book folder থেকে search
    url = "../cart/search_book.php";
    detailsPath = "../book/book_details.php?id=";
}
else
{
    // index.php থেকে search
    url = "cart/search_book.php";
    detailsPath = "book/book_details.php?id=";
}

    fetch(url + "?q=" + q + "&filter=" + filter)
    .then(response => response.json())
    .then(data => {
        let html = "";
        data.forEach(book => {
            html += `
            <div class="card">
                <img src="images/<?php echo htmlspecialchars($row['image_path']); ?>"> 
                <h2>${book.title}</h2>
                <p>${book.author}</p>
                <p>${book.price} Tk</p>
                <a href="${detailsPath}${book.id}">View Details</a>
            </div>
            `;
        });
        document.getElementById("bookContainer").innerHTML = html;
    })
    .catch(err => {
        console.error("Error:", err);
    });
}