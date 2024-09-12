<body>
    @vite(['resources/js/common.js', 'resources/css/app.css'])
	
	<div class="container-login" style="background-image: url('images/page/fondo_pantalla.jpg');">
		<div class="box-login">
			<div class="cover">
     
            </div>
			<div class="content-login">
				<div class="form-login">
					<h2>Iniciar sesión</h2>
					<form action="{{ route('login.verify') }}" method="POST">
						@csrf
						<div class="inputBox-login">
							<input class="input-log" type="text" name="username" readonly onfocus="this.removeAttribute('readonly');" required>
							<i class="fa-solid fa-user" style="color: var(--blue-strong)"></i>
							<span class="title-input">Usuario</span>
						</div>
						<div class="inputBox-login">
							<input class="input-log" type="password"  name="password" id="password" readonly onfocus="this.removeAttribute('readonly');" required>
							<i class="fa-solid fa-lock" style="color: var(--blue-strong)"></i>
							<span class="title-input">Contraseña</span>
                            <span id="togglePassword" style="cursor: pointer;">
                                <i class="fa-solid fa-eye" style="color: var(--blue-strong)"></i>
                            </span>
						</div>
						<div class="inputBox-login">
							<input class="input-log" type="submit" value="Iniciar sesión">
						</div>
                        <div class="message_login">
                            {{ $errors->first('message') }}
                        </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
<script src="https://kit.fontawesome.com/e342c8a50b.js" crossorigin="anonymous"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        var passwordField = document.getElementById('password');
        var iconElement = this.querySelector('i');
        var fieldType = passwordField.type;

        if(passwordField.value.length > 0) {
            if (fieldType === 'password') {
                passwordField.type = 'text';
                iconElement.classList.remove('fa-eye');
                iconElement.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                iconElement.classList.remove('fa-eye-slash');
                iconElement.classList.add('fa-eye');
            }
        }
    });
</script>
</html>