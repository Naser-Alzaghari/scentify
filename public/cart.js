'use strict'

console.log("hello world");

 var quantity = document.getElementById("quantitiy");

 quantity.addEventListener("input", function() {
    // Update the display text with the current input value
    quantity.textContent = "You typed: " + quantity.value;
});