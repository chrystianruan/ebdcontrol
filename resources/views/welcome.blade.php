<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="/css/login.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link rel="icon" type="imagem/png" href="/img/logo_ebd.png" />
  <title>Login</title>
</head>
<body>
  <div class="container">
    <div style="display: flex; justify-content:center; flex-wrap: wrap; margin: 2%">
      <img width=90 src="/img/logo-adpar.png">
      <img width=70  src="/img/logo_ebd.png">
    </div>
      <div class="wrapper">
        <div class="title"><span>Login - EBDControl</span></div>
        <form method="POST" action="/">
          @csrf
            @if ($errors->any())
            <div class="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif
          @if (session('danger'))
              <div class="alert">
                  {{session('danger')}}
              </div>
          @endif
          <div class="row">
           
            <i class="bx bx-user-circle"></i>
            <input type="text" placeholder="Nome de usuário" name="username" required value="{{old('username')}}">
          </div>
          <div class="row">
            <i id="olho" class="bx bx-lock-alt" style="cursor: pointer"></i>
            <input type="password" id="senha" placeholder="Senha" name="password" required>
            
          </div>
          <div class="pass"><a href="/forgot-password">Esqueceu a senha?</a></div>
          <div class="row button">
            <input type="submit" value="Login">
          </div>
        </form>
      </div>
    </div>
  
  
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
var senha = $('#senha');
var olho= $("#olho");
    olho.mousedown(function() {
      senha.attr("type", "text");
    });

    olho.mousedown(function() {
      olho.attr("class", "bx bx-lock-open");
    });

    olho.mouseup(function() {
      senha.attr("type", "password");
    });

    olho.mouseup(function() {
      olho.attr("class", "bx bx-lock-alt");
    });

    // para evitar o problema de arrastar a imagem e a senha continuar exposta, 
    //citada pelo nosso amigo nos comentários
    $( "#olho" ).mouseout(function() { 
      $("#senha").attr("type", "password");
    });

</script>

</html>



 