@extends('layout.master')

    @section('title','Add Brand')
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
                            <h4 class="card-title">Add Brand </h4>
                        </div>
                        
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form-horizontal" novalidate action="{{url('add-brand')}}" method="post" enctype="multipart/form-data">  
                                    @csrf
                                    @if (Session::has('message'))
                                            <div class="alert alert-success">{{ Session::get('message') }}</div>
                                    @endif
                                    <div class="form-group">
                                        <div class="product-image">
                                            <input accept="image/*" type='file' id="imgInp" name="photo" class="d-none"required />
                                            <img class="img-fluid" id="blah"  src="app-assets/images/default.png" alt="your image" required/>    
                                        </div><!-- product image -->
                                    </div>

                                    <div class="form-group">
                                        <label> Brand Name  </label>
                                        <div class="controls">
                                            <input type="text" name="name" class="form-control" placeholder="Brand Name" required>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label> Phone  </label>
                                        <div class="controls">
                                            <input type="number" name="phone" class="form-control" placeholder="Phone" required>
                                        </div>
                                    </div> 
                                    
                                    <div class="form-group">
                                        <label> Website Url  </label>
                                        <div class="controls">
                                            <input type="text" name="website_url" class="form-control" placeholder="Website url" >
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label> Brand Description  </label>
                                        <div class="controls">
                                            <textarea class="form-control" placeholder="Brand Description" name="description" id="" rows="5"></textarea>
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
