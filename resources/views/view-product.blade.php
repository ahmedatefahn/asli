@extends('layout.master')

    @section('title','View Product')
    @section('content')
    
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">

                <section class="brand">
                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <div class="card">
                                <div class="card-content">
                                    <img class="card-img-top img-fluid" src="{{asset('/')}}{{$product->photo}}" alt="Card image cap">
                                    <h2 class="mt-2 text-center">{{$product->name}} </h2> 
                                    <div class="card-body p-0">
                                        <p class="card-text  mb-0"> {{$product->description}}</p> 
                                    </div>
                                </div>
                            </div> <!-- card --> 

                        </div>

                        <div class="col-md-6 mb-3">

                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body p-0">
                                                    
                                        <div class="special-order-section">
 

                                                <h3 class="custom-headline">  Barcodes  </h3>

                                                <div class="order-input">
                                                    <input id="myInput" placeholder=" Add Barcode " class="form-control" type="text">
                                                </div> <!-- order-input -->


                                                <div class="submit-btn">
                                                    <a id="myBtn" type="submit" class="btn btn-primary add-order-to-list" href="#">  Add Barcode </a>
                                                </div> <!-- submit-btn -->
                                                
                                                <div class="all-orders mt-2"></div> <!-- all-orders -->

                                        </div> <!-- special-order-section -->



                                        <div class="barcodes-list  d-none">
                                            <ul>
                                                
                                                @foreach ( $product->Barcodes as $barcode )
                                                    
                                                
                                                    <li class="barcode mt-2">
                                                        <div class="barcode"> {{$barcode->code}} </div>
                                                        <div class="btns">
                                                            <button type="button" class="btn mr-1   btn-primary btn-sm waves-effect waves-light">Edit</button>
                                                            <button type="button" class="btn   btn-danger btn-sm waves-effect waves-light">Delete</button>
                                                        </div>
                                                    </li> <!-- barcode -->
                                                @endforeach    
                                                
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- card --> 

                        </div>

                    </div><!-- row -->
                </section>

            </div> <!-- content-body -->
        </div>
    </div>
    <!-- END: Content-->
    @endsection

@section('js')

<script type="text/javascript">

        var allOrderValues = [];
        // final values will store here

        $(".add-order-to-list").on("click", function(){



        var orderInput = $(".order-input input");

        var orderVal = orderInput.val();

        var oneOrderDesign = `
            <div class="one-order">
            <p>  ${orderVal} </p>
            <i class="fa fa-trash delete-this-order" data-toggle="tooltip" data-placement="top" title="  delete  " ></i>
            </div>
        `

        if( $(orderInput).val() ) { 

            $(".all-orders").append(oneOrderDesign);

            orderInput.val("")

            allOrderValues.push(orderVal)

            // console.log(allOrderValues);

            $('[data-toggle="tooltip"]').tooltip();

        }

        });

        $(document).on("click", ".delete-this-order",  function () {

        $(this).parent(".one-order").remove();

        thisIndex = $(this).parent(".one-order").index();

        allOrderValues.splice(thisIndex,1);

        console.log(allOrderValues);

        });


        $("#pass").keypress(function(event) { 
            if (event.keyCode === 13) { 
                $("#GFG_Button").click(); 
            } 
        }); 

        $("#GFG_Button").click(function() { 
            alert("Button clicked"); 
        }); 

        var input = document.getElementById("myInput");
        input.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("myBtn").click();
            }
        }); 



 
</script>
@endsection