<?php $this->load->view('modules/kanban/kanban_heading', array('title' => 'Projects')); ?>



<div class="list-group">
    <a href="#" onclick="kanban.project.open_create(); return false;" class="list-group-item"><span class="glyphicon glyphicon-plus"></span> Create new project</a>
    <?php
    foreach($projects as $project)
    {
        ?>
        <a href="/project/<?php echo $project->id; ?>" class="list-group-item"><?php echo $project->title; ?></a>
    <?php
    }
    ?>
</div>

<div id="lightboxContainer"></div>