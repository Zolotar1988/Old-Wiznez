<?php
$section_width = (count($sections) ? floor(100 / count($sections)) : 0);
$section_width = $section_width - 6;
?>


<?php $this->load->view('modules/kanban/board_sidebar'); ?>
<?php $this->load->view('modules/kanban/kanban_heading', array('title' => $board->title, 'boardId' => $board->id)); ?>


<div id="kanban_board" style="" class="cf">

    <?php
    foreach($sections as $section)
    {
        ?>
        <div class="kanban_section" id="kanban_section-<?php echo $section->id; ?>" data-id="<?php echo $section->id; ?>" style="width: <?php echo $section_width; ?>%">
            <div class="section_title cf">
                <span class="titlex"><?php echo $section->title; ?></span>

                <div class="section_dropDownActions" style="margin-right: 70px">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">Move cards<span class="caret"></span></button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1"><?php foreach($sections as $section2) echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="#" onclick="kanban.section.moveCards(' . $section->id . ', ' . $section2->id . ')">To section ' . $section2->title . '</a></li>'; ?></ul>
                    </div>
                </div>

                <div class="section_add" onclick="kanban.task.open_create(<?php echo $project->id; ?>, <?php echo $board->id; ?>, <?php echo $section->id; ?>)" data-toggle="tooltip" title="" data-original-title="Create a new card" data-placement="right"><span class="glyphicon glyphicon-plus"></span></div>
                <div class="section_delete" onclick="kanban.section.delete(<?php echo $section->id; ?>)" data-toggle="tooltip" title="" data-original-title="Delete this section" data-placement="right"><span class="glyphicon glyphicon-minus"></span></div>
            </div>
            <div class="task_container" id="task_container"><?php foreach($section_tasks[$section->id] as $task) $this->load->view('renderers/kanban/task_row', array('task' => $task)); ?></div>
        </div>
    <?php
    }
    ?>
</div>


<script type="text/javascript">
    var preventCardClicking = false;
    jQuery(function($) {
        var panelList = $('.kanban_section .task_container');

        panelList.sortable({
            // Only make the .panel-heading child elements support dragging.
            // Omit this to make the entire <li>...</li> draggable.
//            handle: '.kanban_task',
            connectWith: '.kanban_section .task_container',
            start: function(event, ui) { preventCardClicking = true; },
            update: function() {
                $('.kanban_task', panelList).each(function(index, elem) {
                    var $listItem = $(elem),
                        newIndex = $listItem.index();
                });

                kanban.section.reOrderTasks();
            }
        });
    });
</script>

<div id="lightboxContainer"></div>
<div id="taskCreatingAndEditingLightboxContainer"></div>