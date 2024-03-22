document.addEventListener('DOMContentLoaded', function () {

// ############################### Sorting ##################################################

    let thead = document.getElementById('thead');
    let tbody = document.getElementById('tbody');

    thead.addEventListener('click', function (e) {
        const th = e.target;
        if (th.tagName !== 'TH' || !th.hasAttribute('data-type')) return;
        const inverse = th.classList.contains('sorted-down') ? -1 : 1;
        clearSortIcons();
        th.classList.add(inverse < 0 ? 'sorted-up' : 'sorted-down');
        sort(th.cellIndex, th.dataset.type, inverse)
    })

    function sort(index, type, inverse) {
        let rows = Array.from(tbody.rows);
        let sortFunc;
        switch (type) {
            case 'int':
                sortFunc = function (a, b) {
                    return (+a.cells[index].innerHTML - +b.cells[index].innerHTML) * inverse;
                }
                break;
            default:
                sortFunc = function (a, b) {
                    return a.cells[index].innerHTML.toLowerCase() > b.cells[index].innerHTML.toLowerCase()
                        ? inverse : -inverse;
                }
                break;
        }
        rows.sort(sortFunc);
        tbody.append(...rows);
    }

    function clearSortIcons() {
        for (let elem of thead.children[0].children) {
            if (!elem.hasAttribute('data-type')) continue;
            elem.classList.remove('sorted-down');
            elem.classList.remove('sorted-up');
        }
    }

// ############################### Drag & Move ##################################################

    tbody.addEventListener('dragstart', function (e) {
        e.target.closest('tr').classList.add("captured");
    });
    tbody.addEventListener('dragend', function (e) {
        e.target.closest('tr').classList.remove("captured");
    });
    tbody.addEventListener('dragover', function (e) {
        e.preventDefault();
        const current = e.target.closest('tr');
        if (!current.classList.contains('draggable')) return;

        const active = document.querySelector("tr.captured");
        const center = current.getBoundingClientRect().y + current.getBoundingClientRect().height / 2;
        const next = (e.clientY < center) ?
            current :
            current.nextElementSibling;
        if (next === active || next && next.previousElementSibling === active) return;

        tbody.insertBefore(active, next);
        clearSortIcons();
    });

// ############################### Deleting ##################################################

    const deleteBut = document.getElementById('delete-button');
    let deleteArray = [];
    tbody.addEventListener('change', function (e) {
        const checkbox = e.target;
        if (checkbox.tagName !== 'INPUT'
            || checkbox.getAttribute('type') !== "checkbox"
            || !checkbox.hasAttribute('data-user-id')) return;
        const userId = +checkbox.dataset.userId;
        if (checkbox.checked) deleteArray.push(userId);
        else deleteArray = deleteArray.filter(n => n !== userId);
        deleteBut.style.display = deleteArray.length > 0 ? 'block' : 'none';
    })

    deleteBut.addEventListener('click', function (e) {
        deleteUser(deleteArray).then(function (data) {
            if (data.status === 'success') clearRows();
        });
    })

    async function deleteUser(data) {
        let response = await fetch('/api/v1/user/remove', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })

        return await response.json();
    }

    function clearRows() {
        for (let item of deleteArray) {
            const elem = document.getElementById('user' + item);
            if (elem) elem.remove();
        }
        deleteArray = [];
        deleteBut.style.display = 'none';
    }

// ############################### Adding ##################################################

    const addDialog = document.getElementById('add-dialog');
    const addButton = document.getElementById('add-button');
    const sendAddButton = document.getElementById('add-send-button');

    for (let cancel of document.querySelectorAll('.cancel')) {
        cancel.addEventListener('click', function (e) {
            e.target.closest('dialog').close();
        })
    }

    addButton.addEventListener('click', function (e) {
        addDialog.showModal();
    })
    document.getElementById('show-password').addEventListener('change', function (e) {
        document.getElementById('inp-add-password').type = e.target.checked ? 'text' : 'password';
    })
    addDialog.showModal();

    sendAddButton.addEventListener('click', function (e) {
        e.preventDefault();
    })
})