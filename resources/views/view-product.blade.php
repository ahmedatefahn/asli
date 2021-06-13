@extends('layout.master')

    <style>
        .pagination .page-item.active .page-link {
            background-color: #bf8839 !important;
        }
        button#myBtn {
            position: absolute;
            top: 0;
            right: 0;
        }
    </style>


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
                                                @if (Session::has('message'))
                                                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                                                @endif
                                                <div class="order-input">
                                                    <input id="myInput" placeholder=" Add Barcode " class="form-control" type="text">
                                                    <button id="myBtn" class="btn btn-primary add-order-to-list">  Add Barcode </button>
                                                </div> <!-- order-input -->


                                                <div class="all-orders mt-2">
                                                    @foreach ( $product->Barcodes as $barcode )
                                                    <div class="one-order">
                                                        <p>  {{$barcode->code}} </p>
                                                        <div class="actions">
                                                            @if ($barcode->scan_before == 1)
                                                                <span class="badge badge-warning"> Scaned </span>
                                                            @else
                                                                <span class="badge badge-success"> Not Scaned </span>
                                                            @endif
                                                            <a href="{{url('delete-barcode/'.$barcode->id)}}">
                                                                <i class="fa fa-trash delete-this-order" data-toggle="tooltip" data-placement="top" title="" data-original-title="  delete  "></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    @endforeach    
                                                    
                                                </div> <!-- all-orders -->

                                                <div class="d-none">
                                                    <nav aria-label="Page navigation example">
                                                        <ul class="pagination justify-content-center mt-3">
                                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">5</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">6</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">7</a></li>
                                                        </ul>
                                                    </nav>
                                                </div>

                                                
                                                <div class="submit-btn">
                                                    <form id="add-barcode" action="{{url('add-barcodes')}}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="barcodes" id="hidden-input" class=""/>
                                                        <input type="hidden" name="product_id" value="{{$product->id}}"/>
                                                        <button id="submitBtn" type="submit" class="btn btn-primary add-order-to-list">  Submit  Barcodes </button>
                                                    </form>
                                                </div> <!-- submit-btn -->
                                                


                                        </div> <!-- special-order-section -->


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

        $(".add-order-to-list").on("click", function(){

            var orderInput = $(".order-input input");

            var orderVal = orderInput.val();

            var scannedLabel = `<span class="badge badge-success"> Not Scaned </span>`

            var oneOrderDesign = `
                <div class="one-order">
                    <p>  ${orderVal} </p>
                    <div class="actions">
                        ${scannedLabel}
                    </div>
                </div>
            `

            if( $(orderInput).val() ) { 

                $(".all-orders").append(oneOrderDesign);

                orderInput.val("")

                allOrderValues.push(orderVal)

                // console.log(allOrderValues);

                $('[data-toggle="tooltip"]').tooltip();

                console.log(allOrderValues);

                $("#hidden-input").val(allOrderValues)

            }

        });

        // $(document).on("click", ".delete-this-order",  function () {

        //     $(this).parents(".one-order").remove();

        //     thisIndex = $(this).parents(".one-order").index();

        //     allOrderValues.splice(thisIndex,1);

        //     console.log(allOrderValues);

        //     $("#hidden-input").val(allOrderValues)

        // });


        var input = document.getElementById("myInput");
        input.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("myBtn").click();
            }
        });



 
</script>
@endsection