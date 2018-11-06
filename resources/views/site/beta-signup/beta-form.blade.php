<!-- SignUp Model -->
<div class="modal fade common-model" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h2 class="title">We’re Launching Soon.</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="fas fa-times"></i></span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
        	<div class="col-sm-6 col-md-6">
        		<img src="{{ asset('front/img/bird-img.png')}}" alt="bird" title="" class="img-fluid">
        	</div>
        	<div class="col-sm-6 col-md-6">
        		<p>Sign up for early access to Fynches and keep a lookout for updates and amazing giveaways.</p>
        		 {!! Form::open(array('url'=>'/betaSignupUser','class'=>'form-horizontal','method'=>'POST','id'=>'betaForm')) !!}
             {{ csrf_field() }}

        			<div class="form-group">
        				{!! Form::label('firstName', 'First Name'); !!}
        	        {!! Form::text('firstName',null,array('class'=>'form-control','id'=>'firstName','name'=>'firstName')) !!}
					</div>
					<div class="form-group">
					  {!! Form::label('email', 'Email'); !!}
        	        {!! Form::email('email',null,array('class'=>'form-control','id'=>'email','required','name'=>'email')) !!}
					</div>
					<button type="submit" class="btn common blue">JOIN OUR BETA LAUNCH</button>
        		 {!! Form::close() !!}
        	</div>
          <div id="betaSuccessMessage"></div>

        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="{{ asset('front/js/jquery.min.js')}}"></script>
{{Html::script("/front/common/beta-signup/betasignup.js")}}
<script type="text/javascript">
   

</script>