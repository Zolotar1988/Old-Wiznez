<div class="kanban_task cf" data-id="<?php echo $task->id; ?>" id="task_row-<?php echo $task->id; ?>">

    <div class="tag_color" style="background-color: <?php echo $task->color; ?>"></div>

    <div class="task_title"><span onclick="kanban.task.open_edit(<?php echo $task->id; ?>, <?php echo $task->project_id; ?>, <?php echo $task->board_id; ?>, <?php echo $task->section_id; ?>)"><?php echo $task->title; ?></span><div class="task_close" onclick="kanban.task.delete(<?php echo $task->id; ?>)">x</div></div>

    <div class="task_description"><?php echo ($task->description ? $task->description : ''); ?></div>

 
    <div class="task_files">
        <div class="title">Files uploaded</div>
        <?php
        foreach($this->kanban->task_files($task->id) as $file)
        {
            ?>
            <div class="file_row cf">
                <a target="_blank" href="/files/<?php echo $file->uploader; ?>/<?php echo $file->uploadedName; ?>"><?php echo $file->uploadedName; ?></a>
            </div>
            <?php
        }
        ?>
    </div>

    <div class="task_members">
    </div>


</div>