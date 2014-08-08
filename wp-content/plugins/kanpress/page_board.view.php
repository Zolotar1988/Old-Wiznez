<div class="wrap">
	<div id="icon-kanpress" class="icon32"><br></div>
	<h2>Kanpress <a href="javascript:void(0)" class="add-new-h2"><?php _e('New task', 'kanpress') ?></a></h2>

	<div class="kanpress-container tres-col">
		<div class="col" id="col1">
			<h3><?php _e('Proposed', 'kanpress') ?> <span></span></h3>
			<div class="tasks-area">
				<?php foreach ($tasks_proposed as $task) : ?>
				<?php kanpress_html_task($task, $categories) ?>
				<?php endforeach ?>
			</div>
		</div>
		<div class="col" id="col2">
			<h3><?php _e('Work in progress', 'kanpress') ?> <span></span></h3>
			<div class="tasks-area">
				<?php foreach ($tasks_assigned as $task) : ?>
				<?php kanpress_html_task($task, $categories) ?>
				<?php endforeach ?>
			</div>
		</div>
		<div class="col" id="col3">
			<h3><?php _e('Under review', 'kanpress') ?> <span></span></h3>
			<div class="tasks-area">
				<?php foreach ($tasks_pending as $task) : ?>
				<?php kanpress_html_task($task, $categories) ?>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>

<div id="form-nueva">
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" class="kanpress-form form-nueva">
		<table class="form-table">
			<tbody>
				<tr class="<?php invalido('resumen', $validation) ?>">
					<th><label for="resumen"><?php _e('Summary', 'kanpress') ?>:</label></th>
					<td>
						<input id="resumen" name="resumen" type="text" class="regular-text" value="<?php echo stripslashes(htmlentities(post('resumen', true))) ?>" />
						<span class="description"></span>
						<div class="val"><?php if (isset($validation['resumen'])) echo $validation['resumen'] ?>
					</td>
				</tr>
				<tr>
					<th><label for="descripcion"><?php _e('Description', 'kanpress') ?>:</label></th>
					<td>
						<textarea id="descripcion" name="descripcion" type="text" class="regular-text" cols="25" rows="5"><?php echo stripslashes(htmlentities(post('descripcion', true))) ?></textarea>
						<span class="description"></span>
					</td>
				</tr>
				<tr>
					<th><label for="categoria"><?php _e('Section', 'kanpress') ?>:</label></th>
					<td>
						<?php echo form_select('categoria', $categories, post('categoria', true), null, null) ?>
						<span class="description"></span>
					</td>
				</tr>
				<tr>
					<th><label for="prioridad"><?php _e('Priority', 'kanpress') ?>:</label></th>
					<?php $prioridad = 1; //Por defecto ?>
					<?php if (post('prioridad', true)) $prioridad = post('prioridad', true); ?>
					<td>
						<?php echo form_select('prioridad', array( __( 'low', 'kanpress' ), __( 'normal', 'kanpress' ), __( 'high', 'kanpress' ) ), $prioridad, null, null) ?>
						<span class="description"></span>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>

<div id="asignar-tarea">
	<form method="post" action="<?php echo KANPRESS ?>'/ajax_assign_task.php" class="kanpress-form asignar-tarea">
		<input type="hidden" name="taskId" id="taskId" />

		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="categoria" style="width: auto"><?php _e('Assigned to', 'kanpress') ?></label></th>
					<td>
						<?php echo form_select('user', $users, null, null, null) ?>
						<span class="description"></span>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
