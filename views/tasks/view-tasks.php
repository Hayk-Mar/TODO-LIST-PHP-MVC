<div class="container m-5 p-2 rounded mx-auto bg-light shadow">
    <div class=" m-1 p-4">
        <h1 class="p-1 text-dark text-center mx-auto display-inline-block">
            <i class="fas fa-check bg-dark text-white rounded p-2"></i>
            <u>Список заданий</u>
        </h1>
    </div>

    <div class="text-right mr-1 px-5">
        <?php if (isset($this->loggedIn)) { ?>
            <a class="confirmLink" confirmText="Вы уверены, что хотите выйти?" href="<?php echo base_url; ?>tasks/adminLogOut">Выйти</a>
        <?php } else { ?>
            <a href="javascript:void(0)" data-toggle="modal" data-target="#adminLogin">Авторизоваться</a>
        <?php } ?>
    </div>

    <div class="mx-4 border-bottom"></div>

    <div class="d-flex justify-content-between align-items-end m-1 p-3 px-5 taskToolsBlock">
        <form id="taskFilterForm" class="d-flex" action="<?php echo base_url; ?>tasks/view_tasks/<?php echo $this->page; ?>">
            <div>
                <label class="text-secondary my-2 pr-2">Филтры</label>
                <select name="status" class="custom-select custom-select-sm btn my-2 changeSubmit">
                    <option value="">Все</option>
                    <option value="1" <?php if (is_exists('status', $this->uriGetInfo)) echo 'selected'; ?>>Завершенные</option>
                    <option value="0" <?php if (isset($this->uriGetInfo['status']) && $this->uriGetInfo['status'] === '0') echo 'selected'; ?>>Активные</option>
                </select>
            </div>
            <div class="px-2 pr-3 sortingBlock">
                <label class="text-secondary my-2 pr-2">Сортировать по</label>
                <div class="d-flex align-items-center">
                    <select name="field" class="custom-select custom-select-sm btn my-2 mr-1 changeSubmit">
                        <option value="name" <?php if (isset($this->uriGetInfo['field']) && $this->uriGetInfo['field'] == 'name') echo 'selected'; ?>>имени пользователя</option>
                        <option value="email" <?php if (isset($this->uriGetInfo['field']) && $this->uriGetInfo['field'] == 'email') echo 'selected'; ?>>email</option>
                    </select>
                    <label for="filter_desc" class="mb-0">
                        <i class="fas fa-sort-amount-down<?php if (!is_exists('DESC', $this->uriGetInfo)) echo '-alt'; ?> text-dark btn p-1"></i>
                    </label>
                    <input type="checkbox" <?php if (is_exists('DESC', $this->uriGetInfo)) echo ' checked'; ?> id="filter_desc" class="hidden changeSubmit" name="DESC" value="1">
                </div>
            </div>
        </form>
        <div>
            <a href="<?php echo base_url; ?>tasks/add_task" class="btn btn-dark my-2 py-1">Добавить новую задачу</a>
        </div>
    </div>

    <div class="mx-1 px-5 pb-3">
        <?php if (isset($this->tasks) && !empty($this->tasks)) {
            foreach ($this->tasks as $key) { ?>
                <div class="mx-auto mb-2">
                    <div class="row px-3 align-items-center todo-item rounded">
                        <div class="col-auto m-1 p-0 d-flex align-items-center">
                            <h2 class="m-0 p-0">
                                <?php if ($key['is_completed']) { ?>
                                    <i class="far fa-check-circle text-success m-0 p-0"></i>
                                <?php } else { ?>
                                    <i class="fas fa-minus text-warning m-0 p-0"></i>
                                <?php } ?>
                            </h2>
                        </div>

                        <p class="col px-1 m-1 edit-todo-input bg-transparent rounded px-3"><?php echo $key['name']; ?></p>

                        <?php if (isset($this->loggedIn)) { ?>
                            <div class="col-auto m-1 p-0">
                                <a href="<?php echo base_url; ?>tasks/edit_task/<?php echo $key['id'] ?>" class="btn btn-primary">
                                    <i class="fas fa-pencil-alt m-0 p-0"></i>
                                </a>
                                <a href="<?php echo base_url; ?>tasks/delete_task/<?php echo $key['id'] ?>" confirmText="Вы уверены, что хотите удалить это задание ?" class="btn btn-danger confirmLink">
                                    <i class="fas fa-trash m-0 p-0"></i>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="mb-2"><?php echo $key['text']; ?></div>
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h6><?php echo $key['email']; ?></h6>
                        <?php if (is_exists('is_admin_changed', $key)) { ?>
                            <h6 class="small_info">Отредактировано администратором</h6>
                        <?php } ?>
                    </div>
                </div>
                <hr>
            <?php }
        } else { ?>
            <h2>Заданий не найдено</h2>
        <?php }
        if (isset($this->pagination)) { ?>
            <nav class="mt-4">
                <ul class="pagination">
                    <?php echo $this->pagination; ?>
                </ul>
            </nav>
        <?php } ?>
    </div>
</div>

<?php if (!isset($this->loggedIn)) { ?>
    <div class="modal fade" id="adminLogin" tabindex="-1" aria-labelledby="adminLoginLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="adminLoginForm" action="<?php echo base_url; ?>tasks/adminLogin">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminLoginLabel">Авторизация</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="login">Логин</label>
                        <input type="text" name="login" required class="form-control inputValidation" id="login">
                        <p class="inputValidationAlert">Поле не может быть пустым</p>
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" name="password" required class="form-control inputValidation" id="password">
                        <p class="inputValidationAlert">Поле не может быть пустым</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-dark">Потвердить</button>
                </div>
            </form>
        </div>
    </div>
<?php } ?>