<header id="kanban_heading" class="cf">
    <div class="left">
        <div class="title"><?php echo $title; ?></div>
        <div class="actions">
            <?php
            if(isset($boardId))
            {
                ?>
                <a href="#" onclick="kanban.section.open_create(<?php echo $boardId; ?>)" data-toggle="tooltip" title="" data-original-title="Create a section to contain cards" data-placement="right"><span class="glyphicon glyphicon-plus"></span> Create a new section</a>
            <?php
            }
            ?>


        </div>
    </div>
    </div>

    <div class="right">

        <div class="section_dropDownActions">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">Go to board<span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1"><?php foreach($this->kanban->my_boards() as $row) echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="#" onclick="goTo_board(' . $row->id . ')">' . $row->title . '</a></li>'; ?></ul>
            </div>
        </div>

        <div class="section_dropDownActions">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">Go to project<span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1"><?php foreach($this->kanban->my_projects() as $row) echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="#" onclick="goTo_project(' . $row->id . ')">' . $row->title . '</a></li>'; ?></ul>
            </div>
        </div>

        <?php
        if(isset($boardId))
        {
            ?>
            <div class="section_dropDownActions">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">Move board to project<span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1"><?php foreach($this->kanban->my_projects() as $row) echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="#" onclick="kanban.board.moveToProject(' . $boardId . ',' . $row->id . ')">' . $row->title . '</a></li>'; ?></ul>
                </div>
            </div>
        <?php
        }
        ?>

        <script>
            function goTo_project(id) { window.location.href = '/project/' + id; }
            function goTo_board(id)   { window.location.href = '/board/' + id; }
        </script>
    </div>

</header>