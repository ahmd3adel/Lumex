<div class="single-product">
    <div class="product-image">
{{--        <img src="{{asset('/assets/images/branding/default.webp')}}" alt="#" width="335" height="335">--}}
        <img src="{{$product->fake_url}}" alt="#" width="335" height="335">
{{--        <img src="{{ $product->fake_url }}" alt="#" width="335" height="335">--}}

        <div class="button">
            <a href="product-details.html" class="btn"><i class="lni lni-cart"></i> Add to Cart</a>
        </div>
    </div>
    <div class="product-info">
        <span class="category">Watches</span>
        <h4 class="title">
            <a href="{{route('front.products.show' , $product->name)}}">{{$product->name}}</a>
        </h4>
        <ul class="review">
            <li><i class="lni lni-star-filled"></i></li>
            <li><i class="lni lni-star-filled"></i></li>
            <li><i class="lni lni-star-filled"></i></li>
            <li><i class="lni lni-star-filled"></i></li>
            <li><i class="lni lni-star"></i></li>
            <li><span>4.0 Review(s)</span></li>
        </ul>
        <div class="price">
            <span>{{$product->price}} </span>
             <span class="discount-price">$30440.00</span>

        </div>
    </div>
</div>
