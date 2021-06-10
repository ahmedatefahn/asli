@extends('layout.master')

    @section('title','Add Product')
    @section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Input Validation start -->
                <section class="add-product input-validation"> 
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Product </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form-horizontal" novalidate> 

                                    <div class="form-group">
                                        <div class="product-image">
                                            <input accept="image/*" type='file' id="imgInp" class="d-none"/>
                                            <img class="img-fluid" id="blah" src="app-assets/images/default.png" alt="your image" />    
                                        </div><!-- product image -->
                                    </div>

                                    <div class="form-group">
                                        <label> Product Name  </label>
                                        <div class="controls">
                                            <input type="text" name="text" class="form-control" placeholder="Product Name">
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label> Product Description  </label>
                                        <div class="controls">
                                            <textarea class="form-control" placeholder="Product Description" name="" id="" rows="5"></textarea>
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div> 

                                </form>
                            </div>
                        </div>
                    </div> 
                </section>
                <!-- Input Validation end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
    @endsection




@section('js')
<script>

    imgInp.onchange = evt => {
        const [file] = imgInp.files
        if (file) {
            blah.src = URL.createObjectURL(file)
        }
    }

    $(".product-image img").on("click",function(){
        $("#imgInp").click();
    })

</script>
@endsection
