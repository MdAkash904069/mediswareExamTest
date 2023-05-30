@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" value="{{$title}}" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        <option value="">Select</option>
                        @foreach ($variants as $data)
                            <option disabled>{{$data->title}}</option>
                            @foreach ($data->variantProducts as $item)
                                <option @if($item->variant == @$variantData){{"selected"}}@endif value="{{$item->variant}}">{{$item->variant}}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" value="{{@$price_from}}" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" value="{{@$price_to}}" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" value="{{@$date}}" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th width="15%">Title</th>
                        <th width="45%">Description</th>
                        <th width="30%">Variant</th>
                        <th width="5%">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$data->title}} <br> Created at : {{ $data->created_at->format('d-M-Y') }}</td>
                                {{-- <td>{{Str::limit($data->description, 150)}}</td> --}}
                                <td>{{$data->description}}</td>
                                <td>
                                    <dl class="row mb-0 variant-toggle" style="height: 80px; overflow: hidden">
                                    @foreach ($data->variants as $variant) 
                                        <dt class="col-sm-3 pb-0">
                                            {{$variant->variant_a}}/{{$variant->variant_b}}/{{$variant->variant_c}}
                                        </dt>
                                        <dd class="col-sm-9">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-4 pb-0">Price : {{ number_format($variant->price,2) }}</dt>
                                                <dd class="col-sm-8 pb-0">InStock : {{ number_format($variant->stock,2) }}</dd>
                                            </dl>
                                        </dd>
                                    @endforeach
                                    </dl>
                                    <button class="btn btn-sm btn-link variant-toggle-button">Show more</button>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-3">
                    <p>Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} out of {{ $products->total() }}</p>
                </div>
                <div class="col-md-9">
                    {!! $products->links() !!}
                </div>
            </div>
        </div>
    </div>
    <style>
        .pagination{
            float: right;
        }
        .select2-results__option[aria-disabled=true]{
            background: #c7c2c2 !important;
            padding-left: 20px !important;
            font-weight: bold !important
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.variant-toggle-button').click(function(e){
                e.preventDefault();
                $(this).parent().find('.variant-toggle').toggleClass('h-auto');
            })
        })
    </script>

@endsection
