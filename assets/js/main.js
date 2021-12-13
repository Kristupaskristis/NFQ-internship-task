$(document).ready(function() {
    $('#addStudent').on('click', initStudentForm);
});

groups = JSON.parse(groups);

function initStudentForm()
{
    let tr = document.createElement('tr'),
        td_1 = document.createElement('td'),
        td_2 = document.createElement('td'),
        td_3 = document.createElement('td'),
        name = document.createElement('input'),
        group = document.createElement('select'),
        saveBtn = document.createElement('a'),
        deleteBtn = document.createElement('a'),
        actionsContainer = document.createElement('div')
    ;

    name.type = 'text';
    name.name = 'name';
    name.required = true;

    group.name = 'group';

    saveBtn.href = 'javascript:void(0);';
    saveBtn.innerText = 'Save';
    saveBtn.onclick = saveStudent;


    deleteBtn.href = 'javascript:void(0);';
    deleteBtn.innerText = 'Delete';

    deleteBtn.onclick = function () {
        $(this).closest('tr').remove();
    };

    actionsContainer.className = 'action-group';

    let option = document.createElement('option');
    group.appendChild(option);

    for (let i = 0; i < groups.length; i++) {
        let option = document.createElement('option');

        option.value = groups[i];
        option.text = 'Group #' + groups[i];

        group.appendChild(option);
    }

    actionsContainer.append(saveBtn);
    actionsContainer.append(deleteBtn);

    td_1.append(name);
    td_2.append(group);
    td_3.append(actionsContainer);

    tr.append(td_1);
    tr.append(td_2);
    tr.append(td_3);

    $('#studentsForm').append(tr);
}

function saveStudent() {
    var btn = $(this),
        parent = $(btn).closest('tr'),
        name = parent.find('input[name=name]'),
        fullName = name.val(),
        group = parent.find('select[name=group]').val()
    ;

    $.post('./api/student/create.php', {
        fullName: fullName,
        groupId: group
    }).done(function (r) {
        parent.remove();
        addStudent(r.data);
        location.reload();
    }).fail(function (jqXHR) {
        let r = jqXHR.responseJSON,
            container = document.createElement('div');

        container.className = 'error';
        container.innerText = r.message;

        $('.error').remove();
        $(container).insertAfter($('[name=' + r.name + ']'));
    });
}

function addStudent(student) {
    let tr = document.createElement('tr'),
        td_1 = document.createElement('td'),
        td_2 = document.createElement('td'),
        td_3 = document.createElement('td'),
        deleteBtn = document.createElement('a')
    ;

    deleteBtn.href = 'javascript:void(0);';
    deleteBtn.innerText = 'Delete';
    deleteBtn.setAttribute('data-id', student.id);

    deleteBtn.onclick = () => deleteStudent(student.id);

    td_1.append(student.name);
    td_2.append(student.group_id === null ? '-' : 'Group #' + student.group_id);
    td_3.append(deleteBtn);

    tr.append(td_1);
    tr.append(td_2);
    tr.append(td_3);

    $('#studentsForm').append(tr);

}

function deleteStudent(id) {
    $.ajax({
        type: 'DELETE',
        url: './api/student/delete.php',
        data: JSON.stringify({id: id}),
        contentType: 'application/json',
        dataType: 'json',
        success: function (r) {
            $('a[data-id=' + id + ']').closest('tr').remove();
            location.reload();
        },
    })
}


