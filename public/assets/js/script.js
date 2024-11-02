const add_quantity = document.getElementById("add_quantity");
        const quantity_num = document.getElementById('quantity_num');
        const add_item_id = document.getElementById('add_item_id');
        const cart_image = document.getElementById('cart_image');
        const add_item_title = document.getElementById('add_item_title');
        const cart_price = document.getElementById('cart_price');
        const min_quantity = document.getElementById("min_quantity");
        
        function addbutton($num){
            if(++quantity_num.value >= $num){
                add_quantity.setAttribute("disabled","");
            }
            if(quantity_num.value){
                    min_quantity.removeAttribute("disabled");
                }
        }
        
        min_quantity.addEventListener("click",()=>{
                if(--quantity_num.value < 2){
                    min_quantity.setAttribute("disabled","");
                }
                document.getElementById("add_quantity").removeAttribute("disabled");
        });

        
        