<div class="jumbotron mb-0 bg-light" role="alert">
    <?php if (isset($titulo)):?>
    <h1><?= $titulo?></h1>
    <?php else:?>
    <h1 class="display-4">Acceso Denegado</h1>
    <?php endif?>
    
    <?php if (isset($mensaje)):?>
    <h3><?= $mensaje?></h3>
    <?php else:?>
    <h3>Usted no posee acceso a este módulo, por favor comuniquese con el administrador de usuarios</h3>
    <?php endif?>
    <input type=”button” class="btn btn-primary waves-effect waves-light" onClick="history.back()" value="Regresar">
</div>