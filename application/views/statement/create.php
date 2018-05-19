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
				<h1>Create statement:</h1>
			</div>
		</div>
	</div>
    <div class="row">
        <div class="col-lg-12">
            <form class="form" action="<?= site_url('statement/create'); ?>" method="post">
                <div class="form-group">
                    <label for="statement_value">Statement value</label>
                    <input id="statement_value" class="form-control" name="statement_value" value="<?= $statement; ?>">
                </div>
                <div class="form-group">
                    <label for="parent_statement_value">Statement parent value</label>
                    <select id="parent_statement_value" class="form-control" name="parent_statement_value" size="3">
                        <option></option>
                        <?php foreach ($statements as $statement) : ?>
                            <option <?= $statement == $parent_statement ? 'selected' : ''; ?>>
                                <?php echo $statement; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="parent_relationship_value">Parent relationship value</label>
                    <input id="parent_relationship_value" class="form-control" name="parent_relationship_value" value="<?= $parent_relationship; ?>">
                </div>
                <button type="submit" class="btn btn-default pull-right">Create</button>
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