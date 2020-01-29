<h1>Configuración del pie</h1>
<form method="post">
  <p>
    Texto para footer: <input type="text" name="textopie" value="<?php echo ($valor_option = get_option('valor_footer'))? $valor_option : ''; ?>">
  </p>
  <p>
    <input type="submit" value="Guardar">
  </p>
</form>