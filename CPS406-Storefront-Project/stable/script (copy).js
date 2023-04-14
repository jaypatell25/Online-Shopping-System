/**
this js script just adds the total price counter when you add an item.
i'll probably remove javascript entirely at some point because php can
actually communicate with the database.

tldr; don't bother working on the javascript
*/

let total = 0.00;

let buyButtons = document.getElementsByTagName("button");
let prices = document.getElementsByClassName("price");
for (let i = 1; i < buyButtons.length; i++) {
    let price = prices[i-1].innerHTML.split("$");
    buyButtons[i].addEventListener("click", function(){addProduct(price[1]);});
}



function addProduct(cost){
    const screenTotal = document.getElementById("total");
    total = parseFloat(total) + parseFloat(cost);
    total = total.toFixed(2);
    screenTotal.innerHTML="$" + total;
}

function test(){
    total = 0.00.toFixed(2);
    const screenTotal = document.getElementById("total");
    screenTotal.innerHTML="$" + total;
}