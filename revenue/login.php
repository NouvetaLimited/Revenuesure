<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="description" content="Responsive Admin Template" />
    <meta name="author" content="SmartUniversity" />
    <title>Revenuesure</title>
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
	<!-- icons -->
    <link href="fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="fonts/material-design-icons/material-icon.css" rel="stylesheet" type="text/css" />
    <!-- bootstrap -->
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- style -->
    <link rel="stylesheet" href="assets/css/pages/extra_pages.css">
	<!-- favicon -->
    <link rel="shortcut icon" href="assets/img/favicon.ico" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="background-color:#DFE0E2">
    <div class="form-title">
        <h1>RevenueSure</h1>
    </div>
    <!-- Login Form-->
    <div class="login-form text-center">
        <div class="toggle"><i class="fa fa-user-plus"></i>
        </div>
        <div class="form formLogin">
            <h2>Login to your account</h2>
            <form>
                <input type="text" placeholder="Username" required/>
                <input type="password" placeholder="Password" required/>
                <div class="remember text-left">
                    <div class="checkbox checkbox-primary">
                        <input id="checkbox2" type="checkbox" checked>
                        <label for="checkbox2">
                            Remember me
                        </label>
                    </div>
                </div>
                <button>Login</button>
                <div>
                  <a href="#form">Haven't Registered?</a>
                </div>
                <div class="forgetPassword"><a href="javascript:void(0)">Forgot your password?</a>
                </div>
            </form>
        </div>
        <div class="form formRegister">
            <h2>Create an account</h2>
            <form>
                <input type="text"  placeholder="Id Number" required/>
                <input type="email"  placeholder="Email Address" required/>
                <input type="tel"  placeholder="Phone Number" required/>
                <input type="password" placeholder="Enter Password" required/>
                <input type="password"  placeholder="Repeat Password" required/>
                <div class="checkbox checkbox-primary">
                    <input id="checkbox2" type="checkbox" checked>
                    <label for="checkbox2">
                        Accept Terms&Conditions
                    </label>
                </div>


                <button type="submit">Register</button>
            </form>
        </div>

        <div class="form formReset">
            <h2>Reset your password?</h2>
            <form>
                <input type="email" placeholder="Email Address" />
                <button>Send Verification Email</button>
            </form>
        </div>
    </div>
    <script src="assets/plugins/jquery/jquery.min.js" ></script>
    <script src="assets/js/pages/extra_pages/pages.js" ></script>
    <!-- start js include path -->

    <!-- end js include path -->
</body>
</html>
