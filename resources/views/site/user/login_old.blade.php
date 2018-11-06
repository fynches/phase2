{{Html::style("/resources/assets/fonts/customFonts.css")}}
{{Html::style("/resources/assets/css/style.css")}}
{{Html::style("/resources/assets/css/common.css")}}

<title>Menzis Offerte Tool</title>

<div class="site-main">
	<header class="site-header login-header cf">
        <div class="site-logo">
            <a href=""></a>
        </div>
        <div class="header-text"><span>Offertetool</span></div> 
	</header>           
    <div class="intro-section">
        <div class="inner cf">
            <div class="content-wrap">
                <p>Citaat Toepassing</p>
            </div>
        </div>
    </div>

    <div class="main-content-wrap">
        <div class="inner cf">
            {!! Form::open(array('url'=>'/user/login','class'=>'center-div top80','method'=>'POST','id'=>'user_login')) !!}
            <div class="main-content">
                <div class="form-wrapper form-wrapper-login">
                    <div class="title">
                        <h2>Log in</h2>                        
                    </div>
                    <div class="error_msg">
                        @include('errors.common_errors')
                    </div>
                    <div class="form-inputs">
                        <div class="input-field cf">
                            {!! Form::label('email', 'Gebruikersnaam',array('class'=>'col-sm-2 control-label')); !!}
                            <div class="col-sm-10 dev">
                                {!! Form::text('email',null,array('class'=>'form-control','id' =>'email')) !!}
                            </div>
                        </div>

                        <div class="input-field cf">
                            {!! Form::label('password', 'Wachtwoord',array('class'=>'col-sm-2 control-label')); !!}
                            <div class="col-sm-10">
                                {!! Form::password('password', null, array('placeholder' => '','class' => 'form-control','id'=>'password')) !!}
                            </div>
                        </div>

                    </div>
                </div>

                <div class="submit-btn-wrap">
                    <div class="submit-btn">
                        {!! Form::submit('Aanmelden',array('class'=>'', 'id' =>'login', 'style' => 'display:none;')); !!}
                        <a href="javascript:void(0);" onclick="signIn();"> Aanmelden </a>
                        {!! Form::hidden('usertype', 2, array('class' => 'form-control')) !!}
                    </div>	
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{Html::script("/resources/assets/js/jquery-1.12.0.min.js")}}
    {{Html::script("/resources/assets/js/jquery-ui.js")}}
    {{Html::script("/resources/assets/js/jquery.validate.min.js")}}
    {{Html::script("/resources/assets/js/user/user-login.js")}}
</div>
