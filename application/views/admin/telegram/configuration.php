<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.card-wa-configuration {
    padding: 20px;
    background: #ffffff;
    border-radius: 5px;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}
.depField {
    display: none;
}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card-wa-configuration">
                    <div class="table-responsive">
                        <a data-toggle="modal" onclick="addNumber()" class="btn btn-sm btn-success" data-target="#addEditNewPhone">Add New +</a>
                        <div class="card-body">
                            <!-- All Configuration Table -->
                            <table class="table table-clients number-index-2 dataTable no-footer">
                                <thead>
                                <tr role="row">
                                    <th class="toggleable">#</th>
                                    <th class="toggleable">Telegram Name</th>
                                    <th class="toggleable">Telegram Username</th>
                                    <th class="toggleable">Telegram Token</th>
                                    <th class="toggleable">Department / Staff</th>
                                    <th class="toggleable">Webhook</th>
                                    <th class="toggleable">Actions</th>
                                </tr>
                                </thead>
                                <tbody id="tbody">
                                <?php if (!empty($configurationData)) { ?>
                                    <?php foreach ($configurationData as $CD) { ?>
                                        <tr>
                                            <td class="toggleable"><?= $CD['id'] ?></td>
                                            <td class="toggleable"><?= $CD['telegram_name'] ?></td>
                                            <td class="toggleable"><?= $CD['telegram_username'] ?></td>
                                            <td class="toggleable"><?= $CD['telegram_token'] ?></td>
                                            <td class="toggleable">
                                                <?php if ($CD['department_id'] != 0) { ?>
                                                    <?= $CD['department_id'] ?>
                                                <?php } else { ?>
                                                    <?= $CD['staff_ids'] ?>
                                                <?php } ?>
                                            </td>
                                            <td class="toggleable"><a href="<?= $CD['webhook'] ?>" target="_blank">Webhook</a></td>
                                            <td class="toggleable">
                                                <a href="javascript:void(0)" class="btn btn-primary btn-xs" onclick="editNumber(<?= $CD['id'] ?>)">Edit</a>
                                                <a href="<?= admin_url('telegram/delete_configuration/' . $CD['id']) ?>" class="btn btn-danger btn-xs _delete">Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No Configuration Found</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- add/edit Modal -->
<div class="modal fade" id="addEditNewPhone" role="dialog" aria-labelledby="addNewPhone" data-backdrop="static">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Add Telegram Configuration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <form id="configurationForm" action="<?= admin_url('telegram/add_update_configuration') ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" id="config_id" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Telegram Name <i class="fa-solid fa-circle-info text-info" data-toggle="tooltip" data-title="Your display name for identification in Telegram." ></i></label>
                        <input name="telegram_name" id="name" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Telegram Username <i class="fa-solid fa-circle-info text-info" data-toggle="tooltip" data-title="Your unique Telegram handle starting with @." ></i></label>
                        <input name="telegram_username" id="username" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Telegram Token <i class="fa-solid fa-circle-info text-info" data-toggle="tooltip" data-title="API token from BotFather to connect your bot." ></i></label>
                        <input name="telegram_token" id="token" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Department <i class="fa-solid fa-circle-info text-info" data-toggle="tooltip" data-title="Select department for assign" ></i></label>
                        <select name="type" id="type" class="form-control" onchange="typeRender(this)">
                            <option value="0">Select any one option...</option>
                            <option value="1">Department</option>
                            <option value="2">Staff</option>
                        </select>
                    </div>
                    <div class="form-group depField">
                        <label>Department</label>
                        <select name="department_id" id="department" class="form-control">
                            <option value="0">Select a department</option>
                            <?php foreach ($departmentData as $DD) { ?>
                                <option value="<?= $DD['departmentid'] ?>"><?= $DD['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
<script>
function typeRender(type) {
    if (type.value == 1) {
        $('.depField').show();
        $('#department').attr('required', true);
    } else {
        $('.depField').hide();
        $('#department').removeAttr('required');
    }
}

function addNumber() {
    $('#exampleModalLabel').text('Add Telegram Configuration');
    $('#submitBtn').text('Add');
    $('#configurationForm')[0].reset();
    $('#config_id').val('');
    $('.depField').hide();
}

function editNumber(id) {
    const row = $('tr').filter(function () {
        return $(this).find('td:first').text().trim() == id;
    });

    const name = row.find('td:eq(1)').text().trim();
    const username = row.find('td:eq(2)').text().trim();
    const token = row.find('td:eq(3)').text().trim();
    const deptOrStaff = row.find('td:eq(4)').text().trim();

    $('#exampleModalLabel').text('Edit Telegram Configuration');
    $('#submitBtn').text('Update');
    $('#name').val(name);
    $('#username').val(username);
    $('#token').val(token);
    $('#config_id').val(id);

    if (!isNaN(deptOrStaff)) {
        $('#type').val(1);
        typeRender({value: 1});
        $('#department').val(deptOrStaff);
    } else {
        $('#type').val(2);
        typeRender({value: 2});
    }

    $('#addEditNewPhone').modal('show');
}
</script>
</html>
