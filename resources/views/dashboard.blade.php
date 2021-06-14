<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    @section('content')
        <div class="container">
        @if(Session::has('status'))
            <div class="alert alert-success">
                {{  Session::get('status') }}
            </div>
        @endif

            <div class="row">
                @foreach ($products as $product)
                    
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <div class="card">
                            <img class="card-img-top" src="{{$product->image_url}}" alt="Card image cap" height="250" >
                            <div class="card-body">
                                <h4 class="card-title">{{$product->name}}</h4>
                                <h5>PHP {{number_format($product->price,2)}}</h5>
                                <p class="card-text">{{$product->description}}.</p>
                                <p class="card-text"><small class="text-muted">{{$product->quantity}}  piece available</small></p>
                                <form action="{{route('payment')}}" method="post" >
                                    @csrf
                                    <input type="hidden" name="id" value="{{$product->id}}">
                                
                                    <div class="row">
                                            
                                        <div class="col-lg-1"><label for="">Qty</label></div>
                                        <div class="col-lg-4">
                                            <input type="number" name="quantity" class="form-control" max="{{$product->quantity}}"  onKeyDown="return false">
                                        </div>
                                        <div class="col-lg-3">
                                            <input type="submit" class="btn btn-primary" value="Buy Now">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                   
                @endforeach

            </div>


            {{-- <div class="card-group">
                @foreach ($products as $product)
                  
                        
                    <div class="card">
                        <form action="{{route('payment')}}" method="post" >
                            @csrf
                            <input type="hidden" name="id" value="{{$product->id}}">

                            <img class="card-img-top" src="{{$product->image_url}}" alt="Card image cap" height="250" >
                            <div class="card-body">
                                <h4 class="card-title">{{$product->name}}</h4>
                                <h5>PHP {{number_format($product->price,2)}}</h5>
                                <p class="card-text">{{$product->description}}.</p>
                                <p class="card-text"><small class="text-muted">{{$product->quantity}}  piece available</small></p>
                            
                            </div>
                            <div class="card-footer">
                               
                                

                            </div>
                        </form>
                    </div>

          


                        
                    
                @endforeach
               
             
            </div> --}}
        </div>
    @endsection

    
</x-app-layout>
