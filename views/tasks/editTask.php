<div class="container m-5 p-2 rounded mx-auto bg-light shadow">
    <div class=" m-1 p-4">
        <h1 class="p-1 h1 text-dark text-center mx-auto display-inline-block">
            <u>Изменения задания</u>
        </h1>
    </div>

    <div class="mx-4 mb-3 border-bottom"></div>

    <div class="m-1 px-5 text-right">
        <a href="<?php echo base_url; ?>tasks/view_tasks" class="btn btn-dark my-2 py-1">К задачам</a>
    </div>

    <form id="taskControlForm" class="mx-1 mt-2 px-5 pb-3" action="<?php echo base_url; ?>tasks/editTaskRun" method="post">
        <input type="hidden" name="id" value="<?php echo $this->task['id']; ?>">
        <div class="form-group">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <label for="text">Текст</label>

                <div class="custom-control custom-switch">
                    <input type="hidden" name="is_completed" value="0">
                    <input type="checkbox" name="is_completed" value="1" <?php if ($this->task['is_completed']) echo 'checked'; ?> class="custom-control-input" id="isCompletedTask">
                    <label class="custom-control-label" for="isCompletedTask">Отметить как завершенную</label>
                </div>
            </div>
            <textarea name="text" required class="form-control inputValidation" id="text" rows="7"><?php echo $this->task['text']; ?></textarea>
            <p class="inputValidationAlert">Поле не может быть пустым</p>
            <div class="d-flex justify-content-between mt-2 flex-wrap">
                <h5><?php echo $this->task['name']; ?></h5>
                <h5><?php echo $this->task['email']; ?></h5>
            </div>
        </div>
        <div class="text-right">
            <button class="btn btn-dark">Сохранить</button>
        </div>
    </form>
</div>