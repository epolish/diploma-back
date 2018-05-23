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
                <?php $general_statement = $statement; ?>
				<h1>Statement: <?php echo $general_statement; ?></h1>
			</div>
		</div>
	</div>
    <div class="row">
        <div class="col-lg-6">
            <form class="form" action="<?= site_url('statement/update'); ?>" method="post">
                <input type="hidden" name="statement_value" value="<?php echo $statement; ?>">
                <div class="form-group">
                    <label for="new_statement_value">New statement value</label>
                    <input id="new_statement_value" class="form-control" name="new_statement_value" value="<?= $statement; ?>">
                </div>
                <div class="form-group">
                    <label for="new_parent_statement_value">New statement parent value</label>
                    <select id="new_parent_statement_value" class="form-control" name="new_parent_statement_value" size="3">
                        <option></option>
                        <?php foreach ($statements as $statement) : ?>
                            <option <?= $statement == $parent_statement ? 'selected' : ''; ?>>
                                <?php echo $statement; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="new_parent_relationship_value">New parent relationship value</label>
                    <input id="new_parent_relationship_value" class="form-control" name="new_parent_relationship_value" value="<?= $parent_relationship; ?>">
                </div>
                <div class="form-group">
                    <label for="new_parent_relationship_support_level_value">New parent relationship support level value</label>
                    <input id="new_parent_relationship_support_level_value" class="form-control" name="new_parent_relationship_support_level_value" value="<?= $parent_relationship_support_level_value; ?>" type="number">
                </div>
                <button type="submit" class="btn btn-default pull-right">Update</button>
                <a href="<?= site_url('statement/remove/' . $general_statement); ?>" class="btn btn-danger">Delete</a>
            </form>
        </div>
        <div class="col-lg-6">
            <?php if ($parent_statement): ?>
                <h2>Parent statement: </h2>
                <table class="table table-striped table-condensed table-bordered table-hover">
                    <tbody>
                    <tr class="clickable-row" data-href='<?= site_url('statement/get/' . $parent_statement); ?>'>
                        <td>
                            <?php echo $parent_statement; ?> :
                            <?php echo $parent_relationship; ?> :
                            <?php echo $parent_relationship_support_level_value; ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            <?php endif; ?>
            <?php if ($child_statements): ?>
            <h2>Child statements: </h2>
            <table class="table table-striped table-condensed table-bordered table-hover">
                <tbody>
                <?php foreach ($child_statements as $child_statement) : ?>
                    <tr class="clickable-row" data-href='<?= site_url('statement/get/' . $child_statement['statement_value']); ?>'>
                        <td>
                            <?php echo $child_statement['statement_value']; ?> :
                            <?php echo $child_statement['relationship_value']; ?> :
                            <?php echo $child_statement['relationship_support_level_value']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
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