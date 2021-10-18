$(function () {

    //Drag and drop
    $("#task_list").sortable({
        placeholder: 'drop-placeholder'
    })
    .bind('sortupdate', function(e, ui) {
        
        var token = $('#csrf_token').val();
        let order = [];
        $("#task_list .task-row").each(function (index, element) {
            order.push({
                id: $(this).attr("data-id"),
                priority: index + 1,
            });
        });

        let reqParam = {
            action: 'priority',
            order: order,
            _token: token
        };
        updateTaskInfo(reqParam);
        
    });

    //Add Task
    $(".btn-add-task").click(function () {
        initForm();
        $("#task_priority").val(1);
        $("#edit_task_modal").modal('show');
    });

    //Edit Task
    $("body").on('click', '.btn-edit-task', function() {
        let taskID = $(this).closest('li.task-row').data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: gSiteURL + "/task/info/" + taskID,
            success: function (response) {
                if (response.status == "success") {
                    $("#task_id").val(response.data.id);
                    $("#task_name").val(response.data.name);
                    $("#task_priority").val(response.data.priority);
                    $("#edit_task_form").removeClass('was-validated');
                    $("#edit_task_modal").modal('show');
                } else {
                    toastr["error"](response.message);
                    $("#task-row-" + taskID).remove();
                }
            },
        });
    });
    
    //When you submit update / add task
    $("#edit_task_form").submit(function (event) {
        let vForm = $(this);

        if (vForm[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            let reqParam = {
                id: $(this).find("#task_id").val(),
                name: $(this).find("#task_name").val(),
                priority: $(this).find("#task_priority").val(),
                _token: $(this).find("input[name='_token']").val()
            };
            updateTaskInfo(reqParam);
        }
        vForm.addClass("was-validated");
        return false;
    });

    //Delete Task
    $("body").on('click', '.btn-delete-task', function() {
        let taskID = $(this).closest('li.task-row').data('id');
        let token = $('#csrf_token').val();
        bootbox.confirm("Are you sure to delete this task?", function(result){ 
            if (result) {
                //Delete the task
                $.ajax({
                    type: "DELETE",
                    dataType: "json",
                    data: {_token: token},
                    url: gSiteURL + "/task/destroy/" + taskID,
                    success: function (response) {
                        if (response.status == "success") {
                            $("#task-row-" + taskID).remove();
                            toastr["success"](response.message);
                        } else {
                            toastr["error"](response.message);
                        }
                    },
                });
            }
        });
    });

    //Update function with reqParam object
    function updateTaskInfo(reqParam) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: gSiteURL + "/task/update",
            data: reqParam,
            success: function (response) {
                if (response.status == "success") {

                    if (response.hasOwnProperty('html')) {
                        $("#task_list").html(response.html);
                    }

                    $("#edit_task_modal").modal('hide');
                    toastr["success"](response.message);
                    
                } else {
                    toastr["error"](response.message);
                }
            },
        });
    }

    //Init edit/add form
    function initForm() {
        $("#task_id").val('');
        $("#task_name").val('');
        $("#task_priority").val('');
        $("#edit_task_form").removeClass('was-validated');
    }
});
