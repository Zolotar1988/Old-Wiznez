<?php
$isEditing = (isset($task) && isset($task) ? TRUE : FALSE);
?>

<!-- Modal -->
<div class="modal fade" id="newTaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <label id="taskTitleLabel">Title <?php if($isEditing && is_array(json_decode($task->labels, TRUE))) foreach(json_decode($task->labels) as $label) echo '<span class="task_label" onclick="kanban.task.label_remove(\'' . $label . '\', ' . $task->id . ')" style="background:' . $label . ';width: 15px;height: 15px;display: inline-block;position: relative;top: 3px;margin-right: 3px;"></span>'; ?> </label>
                </h4>
            </div>
            <div class="modal-body cf">

                <div class="left">
                    <form id="kanbanForm_newTask" class="cf">

                        <input type="hidden" name="section_id" id="taskCreate_section_id" value="<?php echo $sectionId; ?>">
                        <input type="hidden" name="project_id" value="<?php echo $projectId; ?>">
                        <input type="hidden" name="board_id" value="<?php echo $boardId; ?>">

                        <div class="form-group">
                            <label id="taskTitleLabel">Title <?php if($isEditing && is_array(json_decode($task->labels, TRUE))) foreach(json_decode($task->labels) as $label) echo '<span class="task_label" onclick="kanban.task.label_remove(\'' . $label . '\', ' . $task->id . ')" style="background:' . $label . ';width: 15px;height: 15px;display: inline-block;position: relative;top: 3px;margin-right: 3px;"></span>'; ?> </label>
                            <input type="text" name="title" class="sized_fullWidth"<?php echo ($isEditing ? ' value="' . $task->title . '"' : ''); ?>>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="sized_fullWidth"<?php echo ($isEditing ? ' value="' . $task->description . '"' : ''); ?>>
                        </div>
                    </form>

                    <div class="task_activity cf">
                        <div class="task_activity_title">Activity</div>
                        <div class="task_activity_body"></div>
                    </div>

                    <div class="card_colorPicker" style="display: none">
                        <div class="card_colorPicker_title cf">Labels</div>
                        <?php
                        $availableColors = array('DBDBDB', 'FFB5B5', 'FF9E9E', 'FCC7FC', 'FC9AFB', 'CCD0FC', '989FFA', 'CFFAFC', '9EFAFF', '94D6FF', 'C1F7C2', 'A2FCA3', 'FAFCD2', 'FAFFA1', 'FCE4D4', 'FCC19D');
                        foreach($availableColors as $color) echo '<div class="color_block" style="background-color: #' . $color . ' "></div>';
                        ?>
                    </div>
                </div>

                <div class="right">
                    <?php
                    if($isEditing)
                    {
                        ?>
                        <div class="modal_secondaryTitle">Add</div>
                        <a href="#" class="btn btn-primary" onclick="kanban.task.xxxx()">Members</a>
                        <div id="task_addMemberContainer">
                            <?php
                            foreach($this->wp->users() as $user)
                            {
                                ?>
                                <a href="#" onclick="kanban.task.addMember(<?php echo $task->id; ?>, <?php echo $user->id; ?>)"><?php echo $user->user_nicename; ?></a>
                            <?php
                            }
                            ?>
                        </div>

                        <a href="#" class="btn btn-primary" onclick="kanban.task.label_open()">Labels</a>
                        <div id="taskEdit_labelsContainer" style="display: none"><?php foreach($this->kanban->task_labels() as $label) echo '<div style="background-color: ' . $label . ';width: 100%;height: 30px;margin: 0 0 3px;cursor:pointer;" onclick="kanban.task.label_add(\'' . $label . '\', ' . $task->id . ')" class="task_label"></div>'; ?></div>
                        <a href="#" class="btn btn-primary" onclick="kanban.task.xxxx()">Checklists</a>
                        <input type="text" id="card_checkListItem"><a href="#" class="btn" onclick="kanban.task.todo_add(<?php echo $task->id; ?>)">Add</a>
                        <a href="#" class="btn btn-primary" onclick="kanban.task.dueDateOpen()">Due date</a>
                        <input style="display: none" type="text" class="span2" id="taskDueDate" value="<?php echo $task->due_date; ?>">
                        <script>$('#taskDueDate').datepicker().on('changeDate', function() { $(this).datepicker('hide'); kanban.task.date_due(<?php echo $task->id; ?>); });</script>

                        <?php $this->load->view('renderers/kanban/upload_form', array('taskId' => $task->id)); ?>

                        <div class="modal_secondaryTitle">Manage</div>
                        <a href="#" class="btn btn-primary" onclick="kanban.task.move_open()">Move</a>
                        <select id="moveCardToBoard" style="display: none" onchange="kanban.task.move_selectedBoard(<?php echo $task->id; ?>)"><option value="">Select board to move this to</option><?php foreach($this->kanban->my_boards() as $row) echo '<option value="'. $row->id . '">#' . $row->id . ' - ' . $row->title . '</option>'; ?></select>
                        <?php foreach($this->kanban->my_boards() as $board) { ?><select class="moveCardToSection" id="moveCardToSection-<?php echo $board->id; ?>" style="display: none" onchange="kanban.task.move(<?php echo $board->id; ?>, <?php echo $task->id; ?>)"><option value="">Now select the section</option><?php foreach($this->kanban->board_sections($board->id) as $row) echo '<option value="'. $row->id . '">#' . $row->id . ' - ' . $row->title . '</option>'; ?></select><?php } ?>

                        <a href="#" class="btn btn-primary" onclick="kanban.task.copy(<?php echo $task->id; ?>)">Copy</a>
                        <a href="#" class="btn btn-primary" onclick="kanban.task.subscribeToggle(<?php echo $task->id; ?>)" id="subscribeToTaskBtn-<?php echo $task->id; ?>">Subscribe</a>
                        <a href="#" class="btn btn-primary" onclick="kanban.task.archive(<?php echo $task->id; ?>)">Archive</a>
                        <?php
                    }
                    ?>
                </div>

            </div>
            <div class="modal-footer cf">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="kanban.task.create();">Create</button>
            </div>
        </div>
    </div>
</div>

<script>$("#newTaskModal").modal('show')</script>