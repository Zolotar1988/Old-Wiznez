<!-- Modal -->
<div class="modal fade" id="newProjectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Create a new board</h4>
            </div>
            <div class="modal-body cf">

                <form id="kanbanForm_newSection">

                    <input type="hidden" name="board_id" value="<?php echo $boardId; ?>">

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="sized_fullWidth">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" name="description" class="sized_fullWidth">
                    </div>


                </form>


            </div>
            <div class="modal-footer cf">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="kanban.section.create();">Create</button>
            </div>
        </div>
    </div>
</div>

<script>$("#newProjectModal").modal('show')</script>