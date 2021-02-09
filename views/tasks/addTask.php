<div class="container m-5 p-2 rounded mx-auto bg-light shadow">
    <div class=" m-1 p-4">
        <h1 class="p-1 h1 text-dark text-center mx-auto display-inline-block">
            <u>Новое задание</u>
        </h1>
    </div>

    <div class="mx-4 mb-3 border-bottom"></div>

    <div class="m-1 px-5 text-right">
        <a href="<?php echo base_url; ?>tasks/view_tasks" class="btn btn-dark my-2 py-1">К задачам</a>
    </div>

    <form id="taskControlForm" class="mx-1 px-5 pb-3" action="<?php echo base_url; ?>tasks/addTaskRun" method="post">
        <div class="form-group">
            <label for="name">Имя</label>
            <input type="text" required class="form-control inputValidation" id="name" name="name">
            <p class="inputValidationAlert">Поле не может быть пустым</p>
        </div>
        <div class="form-group">
            <label for="email">Эл. адрес</label>
            <input type="email" required class="form-control inputValidation" id="email" name="email">
            <p class="inputValidationAlert">Поле не может быть пустым</p>
        </div>
        <div class="form-group">
            <label for="text">Текст</label>
            <textarea name="text" required class="form-control inputValidation" id="text" rows="7"></textarea>
            <p class="inputValidationAlert">Поле не может быть пустым</p>
        </div>
        <div class="text-right">
            <button class="btn btn-dark">Создать</button>
        </div>
    </form>
</div>