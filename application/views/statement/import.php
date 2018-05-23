<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a href="<?= site_url('/'); ?>" class="navbar-brand">Expert System Administration</a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="<?= site_url('statement/import'); ?>">Bulk import</a>
                </li>
                <li>
                    <a href="<?= site_url('statement/'); ?>">Statements list</a>
                </li>
                <li>
                    <a href="<?= site_url('statement/tree'); ?>">Statements tree</a>
                </li>
                <li>
                    <a href="<?= site_url('statement/create'); ?>">Create statement</a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <?= $user_name; ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="download">
                        <li>
                            <a href="<?= site_url('auth/logout'); ?>">Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="container main dashboard">
    <?php if(isset($success_message)): ?>
        <div class="row">
            <div class="col-lg-12">
                <br>
                <div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <h4>Success</h4>
                    <p><?= $success_message; ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if(isset($error_message)): ?>
        <div class="row">
            <div class="col-lg-12">
                <br>
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <h4>Error</h4>
                    <p><?= $error_message; ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h1>Bulk import</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <form class="form" action="<?= site_url('statement/import'); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="import_file">Upload csv file</label>
                    <input id="import_file" class="form-control" name="import_file" type="file" accept=".csv">
                </div>
                <div class="form-group">
                    <label for="append_mode">Append mode</label>
                    <input id="append_mode" name="append_mode" type="checkbox">
                </div>
                <button type="submit" class="btn btn-default pull-right">Import</button>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });
</script>