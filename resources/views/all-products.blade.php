@extends('layout.master')

    @section('title','All Products')
    @section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>

            <div class="content-body">
                
                @foreach ( $brands as $brand)
                
                
                    <!-- start brand loop -->
                    <section class="brands">

                        <div class="brand-header">
                            <img class="img-fluid" src="{{$brand->photo}}" />
                            <h2 class="mb-2"> {{$brand->name}}  </h2>
                        </div><!-- brand-header -->

                        <div class="row match-height">
                            
                            
                            @foreach ($brand->products as $product)
                                
                            <!-- start product loop -->
                            <div class="col-xl-3 col-md-6 col-sm-12">
                                <div class="card">
                                    <div class="card-content">
                                        <img class="card-img-top img-fluid" src="{{$product->photo}}" alt="Card image cap">
                                        <div class="card-body">
                                            <h5>{{$product->name}}</h5>
                                            <p class="card-text  mb-0"> {{$product->description}}</p> 
                                            <div class="card-btns d-flex justify-content-between mt-2">
                                                <a href="{{url('product/'.$product->id)}}" class="btn btn-outline-primary w-100">View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- card -->
                            </div><!-- col-xl-3 col-md-6 col-sm-12 -->
                            <!-- end product loop -->
                        
                            @endforeach 



                        </div><!-- row -->

                        <hr />
                    </section> <!-- brands -->
                    <!-- end brand loop -->
                @endforeach
            

            </div> <!-- content-body -->

        </div>
    </div>
    <!-- END: Content-->
    @endsection

