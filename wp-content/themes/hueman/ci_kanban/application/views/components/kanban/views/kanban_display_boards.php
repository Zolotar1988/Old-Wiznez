<?php $this->load->view('modules/kanban/kanban_heading', array('title' => $project->title)); ?>



<div class="list-group">
    <a href="#" onclick="kanban.board.open_create(<?php echo $project->id; ?>); return false;" class="list-group-item"><span class="glyphicon glyphicon-plus"></span> Create new board</a>
    <?php
    foreach($boards as $board)
    {
        ?>
        <a href="/board/<?php echo $board->id; ?>" class="list-group-item"><?php echo $board->title; ?></a>
    <?php
    }
    ?>
</div>

<div id="lightboxContainer"></div>