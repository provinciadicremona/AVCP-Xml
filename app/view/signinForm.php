    <form class="form-signin" action="?mask=signin&amp;do=login" method="post">
        <h2 class="form-signin-heading">Autenticazione</h2>
        <?php if (true === $failedLogin): ?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Autenticazione fallita!</strong><br />Digita nuovamente Login e Password
        </div>
        <?php endif; ?>
        <input type="text" class="input-block-level" name="login" placeholder="Login">
        <input type="password" class="input-block-level" name="password" placeholder="Password">
        <input class="btn btn-large btn-primary" type="submit" value="Accedi" />
    </form>
