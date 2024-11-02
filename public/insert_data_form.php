<form action="insert_data.php" method="post">
            
        <div class="modal fade p-2" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" style="margin: auto; top:50%; -ms-transform: translateY(-50%);
  transform: translateY(-50%);">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add to cart</h5>
                        <!-- Updated close button for Bootstrap 5 -->
        
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- name -->
                        
                        <div class="row">
                                <img class='img-fluid h-100 display-block col-5' 
                                alt='...' id="cart_image" style="width: 200px; height: 200px;">
                            
                            <div class="col-7">
                                <h4 class="" id="add_item_title" name="add_item_title"></h4>
                                <h4 style="color: #705C53;" id="cart_price"></h4>
                                <p id="product_description"></p>
                                <div class="" style="padding: 0 auto;">
                                    <div class="d-inline-flex quantity text-center border border-black rounded mt-3" style="width: fit-content;">
                                    <input class="" id="min_quantity" type="button" value="-" disabled>
                                    <input class="p-2 text-center" name="quantity" id="quantity_num" value="1" style="width:50px" readonly>
                                    <input type="button" value="+" id="add_quantity">
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="add_item_id" id="add_item_id">
                    <div class="modal-footer">
                        <!-- Updated buttons for Bootstrap 5 -->
                        <button type="button" class="btn" style="background-color: darkgrey; color: white;" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary1" name="add_student" value="Add">
                    </div>
                </div>
            </div>
        </div>
    </form>
    