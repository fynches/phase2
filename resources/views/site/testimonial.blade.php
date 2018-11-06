<!-- <section class="two-box-sec">
	<div class="container">
		<div class="row">
			@if(count($testimonails) > 0)
				@foreach($testimonails as $key=>$val)
			<?php
				
		        $defaultPath = 'storage/no-img.jpg';
		        $TestimonaialImage = $val->image;
		
		        if ($TestimonaialImage && $TestimonaialImage != "") {
		            
		            $imgPath = 'storage/testimonialImages/' . $TestimonaialImage;
		           
		            if (file_exists($imgPath))
		            {
		                $imgPath = $imgPath;
		            } else {
		                $imgPath = $defaultPath;
		            }
		        } else {
		            $imgPath = $defaultPath;
		        }?>
				<div class="col-sm-12 col-md-5">
				<div class="color-box">
					<img class="color-box" src="{{ asset($imgPath) }}" alt="" title="">
				</div>
				<blockquote class="blockquote text-center">
					<h3>"{{ $val->name }}"</h3>
				    {!!html_entity_decode($val->description)!!}
				    <footer class="blockquote-footer">{{ $val->author_name }}.</footer>
				 </blockquote>
			</div>
				@endforeach
			@endif
		</div>
	</div>
</section> -->
<!-- Kids and Parents Sec -->
<section class="slider-sec">
  <h2 class="title">Kids and Parents Love Fynches</h2>
  <div class="owl-carousel">
    <div class="item">
      <div class="lft-sec">
      	<div class="rounded-circle">
      		<img src="{{ asset('front/img/Mom.png')}}" alt="" title="" class="img-fluid">
      	</div>
      </div>
      <div class="rgt-sec">
      	<blockquote>
		    <p>Fynches is my go-to birthday solution for my daughter. Love it! </p>
		    <footer>- Heather M.</footer>
		</blockquote>
      </div>
    </div>
    <div class="item">
      <div class="lft-sec">
      	<div class="rounded-circle">
      		<img src="{{ asset('front/img/Couple.png')}}" alt="" title="" class="img-fluid">
      	</div>
      </div>
      <div class="rgt-sec">
      	<blockquote>
		    <p>Fynches is my go-to birthday solution for my daughter. Love it! </p>
		    <footer>- Heather M.</footer>
		</blockquote>
      </div>
    </div>
    <div class="item">
      <div class="lft-sec">
      	<div class="rounded-circle">
      		<img src="{{ asset('front/img/Mom.png')}}" alt="" title="" class="img-fluid">
      	</div>
      </div>
      <div class="rgt-sec">
      	<blockquote>
		    <p>Fynches is my go-to birthday solution for my daughter. Love it! </p>
		    <footer>- Heather M.</footer>
		</blockquote>
      </div>
    </div>
    <div class="item">
      <div class="lft-sec">
      	<div class="rounded-circle">
      		<img src="{{ asset('front/img/Couple.png')}}" alt="" title="" class="img-fluid">
      	</div>
      </div>
      <div class="rgt-sec">
      	<blockquote>
		    <p>Fynches is my go-to birthday solution for my daughter. Love it! </p>
		    <footer>- Heather M.</footer>
		</blockquote>
      </div>
    </div>
  </div>
</section>
<!-- End -->