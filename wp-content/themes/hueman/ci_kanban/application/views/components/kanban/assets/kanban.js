var kanban = {

    temp_vars: {
        taskCreate_sectionId:0
    },

    project: {

        open_create: function() {
            $.get('/create_view?view=project', {}, function(response) {
                $("#lightboxContainer").html(response);
            })
        },

        create: function() {
            $.post('/kanban/kanban_actions_controller/create_project', $("#kanbanForm_newProject").serialize(), function(projectId) {
                window.location.href = '/project/' + projectId;
            });
        }
    },







    board: {

        open_create: function(projectId) {
            $.get('/create_view?view=board', {projectId:projectId}, function(response) {
                $("#lightboxContainer").html(response);
            })
        },
        
        create: function() {
            $.post('/kanban/kanban_actions_controller/create_board', $("#kanbanForm_newBoard").serialize(), function(boardId) {
                window.location.href = '/board/' + boardId;
            });
        },

        moveToProject: function(id, to) {
            $.post('/kanban/kanban_actions_controller/board_moveToProject', {id:id,to:to}, function() {
                window.location.href = '/project/' + to;
            });
        }
    },







    section: {

        open_create: function(boardId) {
            $.get('/create_view?view=section', {boardId:boardId}, function(response) {
                $("#lightboxContainer").html(response);
            })
        },

        create: function() {
            $.post('/kanban/kanban_actions_controller/create_section', $("#kanbanForm_newSection").serialize(), function() {
                window.location.reload();
            });
        },

        reOrderTasks: function() {
            $(".kanban_section").each(function(index, el) {
                el = $(el);
                var sectionId = el.attr('data-id'),
                    sectionOrder = 0;

                el.find('#task_container .kanban_task').each(function(index, el) {
                    sectionOrder++;
                    var taskId = $(el).attr('data-id');
                    kanban.task.update_section(taskId, sectionId, sectionOrder);
                });
            });
        },

        moveCards: function(from, to) {
            $.post('/kanban/kanban_actions_controller/section_moveCards', {from:from,to:to}, function() {
                window.location.reload();
            });
        },

        delete: function(sectionId) {
            var r = confirm("Are you sure you wish to delete this section and it's content?");
            if(r == true)
            {
                $.post('/kanban/kanban_actions_controller/delete_section', {sectionId:sectionId}, function() {
                    window.location.reload();
                });
            }
        }
    },







    task: {

        open_create: function(projectId, boardId, sectionId) {
            kanban.temp_vars.taskCreate_sectionId = sectionId;
            $.get('/create_view?view=task', {projectId:projectId, boardId:boardId, sectionId:sectionId}, function(response) {
                $("#taskCreatingAndEditingLightboxContainer").html(response);
            })
        },

        open_edit: function(taskId, projectId, boardId, sectionId) {
            console.log('wtf');
            if(preventCardClicking === true) { preventCardClicking = false; return false; }
            kanban.temp_vars.taskCreate_sectionId = sectionId;
            $.get('/create_view?view=task', {taskId:taskId, projectId:projectId, boardId:boardId, sectionId:sectionId}, function(response) {
                $("#taskCreatingAndEditingLightboxContainer").html(response);
            })
        },

        create: function() {
            $.post('/kanban/kanban_actions_controller/create_task', $("#kanbanForm_newTask").serialize(), function(response) {
                $("#kanban_section-" + kanban.temp_vars.taskCreate_sectionId + " .task_container").prepend(response);
                $("#newTaskModal").modal('hide');
            });
        },

        title_open: function() {

        },

        dueDateOpen: function() {
            $("#taskDueDate").show();
        },

        subscribeToggle: function(taskId) {
            $.post('/kanban/kanban_actions_controller/task_subscribeToggle', {taskId:taskId}, function(response) { $("#subscribeToTaskBtn-" + taskId).text(response) });
        },

        todo_add: function(taskId) {
            var todo = $("#card_checkListItem").val();
            $.post('/kanban/kanban_actions_controller/card_todoAdd', {taskId:taskId,todo:todo}, function(response) {  });
        },

        todo_remove: function(taskId) {
            $.post('/kanban/kanban_actions_controller/card_todoRemove', {taskId:taskId}, function(response) {  });
        },

        addMember: function(taskId, userId) {
            $.post('/kanban/kanban_actions_controller/card_addMember', {taskId:taskId,userId:userId}, function(response) {  });
        },

        date_due: function(taskId) {
            var dueDate = $("#taskDueDate").val();
            $.post('/kanban/kanban_actions_controller/edit_dueDate', {taskId:taskId,dueDate:dueDate}, function(response) {  });
            $("#taskDueDate").hide();
        },

        label_open: function() {
            $("#taskEdit_labelsContainer").toggle();
        },

        label_add: function(label, taskId)
        {
            $("#taskEdit_labelsContainer").hide();
            $("#taskLabelContainer").append('<span class="task_label" style="background:' + label +  ';width: 15px;height: 15px;display: inline-block;position: relative;top: 3px;margin-right: 3px;"></span>');
            $.post('/kanban/kanban_actions_controller/task_addLabel', {label:label,taskId:taskId}, function(response) {  });
        },

        label_remove: function(label, taskId)
        {
            $.post('/kanban/kanban_actions_controller/task_addLabel', {label:label,taskId:taskId}, function(response) {  });
        },

        copy: function(taskId) {
            $.post('/kanban/kanban_actions_controller/task_copy', {taskId:taskId}, function(response) { window.location.reload(); });
        },

        move_open: function()
        {
            $("#moveCardToBoard").show();
        },

        move_selectedBoard: function(boardId)
        {
            var boardId = $("#moveCardToBoard").val();
            $(".moveCardToSection").hide();
            $("#moveCardToSection-" + boardId).show();
        },

        move: function(boardId, taskId)
        {
            var sectionId = $("#moveCardToSection-" + boardId).val();
            $.post('/kanban/kanban_actions_controller/task_move', {taskId:taskId,boardId:boardId,sectionId:sectionId}, function(response) { window.location.reload(); });
        },

        archive: function(taskId) {
            $.post('/kanban/kanban_actions_controller/task_archive', {taskId:taskId}, function(response) { window.location.reload(); });
        },

        delete: function(taskId) {
            $.post('/kanban/kanban_actions_controller/task_delete', {taskId:taskId}, function(response) { $("#task_row-" + taskId).hide() });
        },

        update_section: function(taskId, sectionId, sectionOrder) {
            $.post('/kanban/kanban_actions_controller/task_update_section', {taskId:taskId, sectionId:sectionId, sectionOrder:sectionOrder}, function(response) {});
        }
    },







    file_upload: {

        file_selected: function () {
            $("#photoUpload-photo_selectBtn").hide();
            $("#photoUpload_photo_progress").show();
        },

        upload_progress: function (file, e) {
            if (e.lengthComputable)
                var percent = Math.round((e.loaded / e.total) * 100);
            if (percent > 95)
                percent = 95;
            $("#photoUpload_photo_progress .bar").css('width', percent + '%');
        },

        upload_complete: function (response) {
            window.location.reload();
            return true;
        }

    }
}