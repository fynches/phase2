@extends('layouts.app')

@section('content')

                <form method="POST" action="{{ route('password.request') }}" id="reset_pass_frm">
                    {!! csrf_field() !!}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-title">
                        <span class="form-title">Welcome.</span>
                        <span class="form-subtitle">Reset Password.</span>
                    </div>
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        <span> Enter any email and password. </span>
                    </div>
                    <div class="form-group">
                        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                        <label class="control-label visible-ie8 visible-ie9">Email</label>
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Enter email address" autocomplete="off" name="email" value="{{ $email ?? old('email') }}" required autofocus> 
                        @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif    
                    </div>
                    <div class="form-group">
                        <label class="control-label visible-ie8 visible-ie9">Password</label>
                        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" /> 
                        @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                    </div>

                    <div class="form-group">
                        <label class="control-label visible-ie8 visible-ie9">Confirm Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm password" required>
                    </div>

                    <div class="form-actions">
                        <div class="pull-left">
                        <button type="submit" class="btn red btn-block uppercase">Submit</button>
                        </div>
                       
                    </div>
                </form>
               
@endsection

<style>
label,.card-header{
  color: #fff;
}
</style>

