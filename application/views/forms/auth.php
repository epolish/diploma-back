<div class="container main sign-in">
    <form class="form-signin" action="<?= site_url('auth/login'); ?>" method="post" role="form">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input name="email" type="email" class="form-control" placeholder="Email address" required="" autofocus="">
        <input name="password" type="password" class="form-control" placeholder="Password" required="">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <?php if(isset($error_message)): ?>
            <br>
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <h4>Error</h4>
                <p><?= $error_message; ?></p>
            </div>
        <?php endif; ?>
    </form>
</div>