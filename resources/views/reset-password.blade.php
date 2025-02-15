<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alterar Senha</title>
</head>
<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <div class="container-fluid w-100 my-5 justify-content-center">
        <div class="card">
            <div class="card-header">
                <h3>Alterar Senha</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-primary" role="alert">
                    <i class="bx bxs-info-circle"></i>
                    Olá, @if (isset(auth()->user()->pessoa_id)) {{ auth()->user()->pessoa->nome }}! @else {{ auth()->user()->matricula }} @endif
                    Para prosseguir com o uso do sistema, é necessário que você altere sua senha.
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger" id="msg_erros_request" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="form-alterar-senha" class="row g-3"  action="/post/reset-password" method="POST">
                    @method('POST')
                    @csrf
                    <div class="col-md-4">
                        <label for="nova-senha" class="form-label">Nova Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="senha" placeholder="Digite sua senha" name="password">
                            <i id="btn-lock-senha" class="bx bx-lock-alt btn btn-outline-secondary" style="cursor: pointer; display: flex; align-items: center; justify-content: center;"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="confirma-senha" class="form-label">Confirmar Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirma-senha" placeholder="Confirme sua senha" >
                            <i id="btn-lock-confirma" class="bx bx-lock-alt btn btn-outline-secondary" style="cursor: pointer; display: flex; align-items: center; justify-content: center;"></i>
                        </div>
                        <div id="senha-error" style="color: red; display: none; margin-top: 5px">As senhas são diferentes!</div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-danger" type="button" id="btn-save">Salvar</button>
            </div>
        </div>

        <div class="text-center">
            <div> <img src="/img/logo_ebd_extend.png" alt="" width="240" class="mt-5"> </div>
        </div>
    </div>
<script
    src="https://code.jquery.com/jquery-3.6.0.js"
    integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous">
</script>
    <script>
        let btn = document.getElementById('btn-alterar')
        let form = document.getElementById('form-alterar-senha');
        let btnSave = document.getElementById('btn-save');

        btnSave.addEventListener("click", function (event) {
            let senha = document.getElementById("senha");
            let confirmaSenha = document.getElementById("confirma-senha");
            let senhaError = document.getElementById("senha-error");

            if (senha.value !== confirmaSenha.value) {
                event.preventDefault();
                senhaError.style.display = "block";
                confirmaSenha.classList.add("is-invalid");
            } else {
                $('#form-alterar-senha').submit();
            }
        });

        function showOrHidePassword(senhaID, btnID) {
            let password = document.getElementById(senhaID)
            let btnLock = document.getElementById(btnID)

            btnLock.addEventListener("click", function() {
                if (password.type === "password") {
                    password.type = "text";
                    btnLock.className = "bx bx-lock-open-alt btn btn-outline-secondary";
                } else {
                    password.type = "password";
                    btnLock.className = "bx bx-lock-alt btn btn-outline-secondary"
                }
            } )
        }

        showOrHidePassword("senha", "btn-lock-senha");
        showOrHidePassword("confirma-senha", "btn-lock-confirma");
    </script>
</body>
</html>
