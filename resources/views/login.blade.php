<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>The Whole Share|Login</title>
    <link rel="stylesheet" href="{{asset('style_admin.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css">
</head>
<body>

    <h1
    style="font-family: 'Times New Roman', Times, serif;
    text-align: center;
    margin-top: 10%;
    font-size: 50px;
    font-weight: bold
    "
    >THE WHOLE SHARE</h1>

    <div class="container" style="margin-left: 15%">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 cardlogin">
            <form action="" method="POST">
            @csrf
                <div class="mb-6">
                @if (Session::has("message"))
                    <h3 style="color: red; font-weight: bold">{{Session::get("message.isi")}}</h3>
                @endif
                <label for="exampleInputEmail1" class="form-label" style="margin-top:50px; font-weight: bold">Username</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="username" aria-describedby="emailHelp" style="width: 80%;" placeholder="Username">
                @error('username')
                <div class="error" style="color: red;font-weight: bold"> {{$message}} </div>
               @enderror
                </div>
                <div class="mb-6">
                <label for="exampleInputPassword1" class="form-label" style="font-weight: bold">Password</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1" style="width: 80%;" placeholder="Password">
                </div>
                @error('password')
                <div class="error" style="color: red;font-weight: bold"> {{$message}} </div>
               @enderror
                <br>
                <button type="submit" name="buttonLogin" class="btn btn-dark" id="btnsubmitlogin" style="width: 20%">Login</button>
            </form>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>


    @include('includes.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
