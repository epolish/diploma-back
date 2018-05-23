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
                <?php if ($statements): ?>
				    <h1>Statements:</h1>
                <?php else: ?>
                    <h1>There are no statements</h1>
                <?php endif; ?>
			</div>
		</div>
	</div>
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <?php
                function getTreeHtml($arr) {
                    $list = '<ul>';

                    foreach ($arr as $key => $value) {
                        if (!empty($value)) {
                            $statementUrl = site_url('statement/get/' . $key);

                            $list .= '<li>';
                            $list .=    "<a href=\"$statementUrl\">";
                            $list .=        $key . getTreeHtml($value);
                            $list .=    '</a>';
                            $list .= '</li>';
                        } else {
                            $statementUrl = site_url('statement/get/' . $key);

                            $list .= '<li>';
                            $list .=    "<a href=\"$statementUrl\">";
                            $list .=        $key;
                            $list .=    '</a>';
                            $list .= '</li>';
                        }
                    }

                    $list .= '</ul>';

                    return $list;
                }

                echo getTreeHtml($statements);
            ?>
        </div>
        <div class="col-lg-4"></div>
    </div>
</div>