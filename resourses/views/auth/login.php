<h1>Iniciar Sesion</h1>
<form method="post">
  
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input value="<?= old('email') ?>" name="email" type="text" class="form-control">
  </div>
  
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input value="<?= old('password') ?>" name="password" type="password" class="form-control">
  </div>

  <div class="text-danger"><?= error('login') ?></div>
  
  <button type="submit" class="btn btn-primary">Enviar</button>
</form>
