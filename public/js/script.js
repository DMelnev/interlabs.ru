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

// ############################### Common ##################################################

        const cellNames =
            {
                id: "td-id",
                login: "td-login",
                name: "td-name",
                email: "td-email",
                address: "td-address",
                edit: "td-edit",
                delete: "td-delete",
            }

        async function sendData(link, data_) {
            let response = await fetch(link, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data_)
            })

            return await response.json();
        }

        function clearErrors(selector) {
            for (let err of document.querySelectorAll(selector)) err.style.display = 'none';
        }

        function clearInputs(inputs) {
            for (let input of inputs) {
                input.addEventListener('focus', function (e) {
                    this.style.background = 'none';
                })
            }
        }

        function errorHandler(messages, errorPrefix, inputPrefix) {
            for (let key in messages) {
                let err = document.getElementById(errorPrefix + key);
                let inp = document.getElementById(inputPrefix + key);
                if (err) {
                    err.innerText = messages[key];
                    err.style.display = 'block';
                }
                if (inp) inp.style.background = '#fff0f0';
            }
        }

// ############################### Editing ##################################################

        const editDialog = document.getElementById('edit-dialog');
        const sendEditButton = document.getElementById('edit-send-button');
        const clearEditQuery = "form.modal-inner .error";
        const inputListEdit = document.querySelectorAll('#edit-dialog input, #edit-dialog textarea');

        let dataForSending;
        initEditButtons();
        clearInputs(inputListEdit);

        function initEditButtons() {
            let editButtons = document.querySelectorAll('tbody .' + cellNames.edit);
            for (let btn of editButtons) {
                btn.addEventListener('click', function (e) {
                    clearErrors(clearEditQuery);
                    showEditDialog(e.target.closest("tr"))
                })
            }
        }

        function showEditDialog(tr) {
            document.getElementById('edit-id').innerText = tr.dataset.userId;
            dataForSending = parseTr(tr);
            for (let key in dataForSending) {
                let elem = document.getElementById('inp-edit-' + key);
                if (elem) elem.value = dataForSending[key];
            }
            editDialog.showModal();
            sendEditButton.addEventListener('click', sendEditData)
        }

        function sendEditData(e) {
            e.preventDefault();
            for (let key in dataForSending) {
                let elem = document.getElementById('inp-edit-' + key);
                if (elem) dataForSending[key] = elem.value;
            }
            sendData('/api/v1/user/edit', dataForSending).then(function (data) {
                clearErrors(clearEditQuery);
                switch (data.status) {
                    case 'success':
                        let tr = document.getElementById('user' + data.data['id'])
                        for (let key in data.data) {
                            let elem = tr.querySelector('.td-' + key)
                            if (elem) elem.innerText = data.data[key];
                        }
                        editDialog.close();
                        break;
                    case 'error':
                        errorHandler(data.messages, 'err-edit-', 'inp-edit-');
                        break;
                    default:
                        console.log(data)
                }
            });

        }

        function parseTr(tr) {
            return {
                id: tr.dataset.userId,
                login: tr.querySelector('.' + cellNames.login).innerText,
                name: tr.querySelector('.' + cellNames.name).innerText,
                email: tr.querySelector('.' + cellNames.email).innerText,
                address: tr.querySelector('.' + cellNames.address).innerText,
            }
        }

// ############################### Insert ##################################################

        const addButton = document.getElementById('add-button');
        if (addButton) {
            const addDialog = document.getElementById('add-dialog');

            const sendAddButton = document.getElementById('add-send-button');
            const inputList = document.querySelectorAll('#add-dialog input, #add-dialog textarea');
            const clearAddQuery = "form.modal-inner .error"
            clearInputs(inputList);

            for (let cancel of document.querySelectorAll('.cancel')) {
                cancel.addEventListener('click', function (e) {
                    e.target.closest('dialog').close();
                })
            }

            addButton.addEventListener('click', function (e) {
                clearErrors(clearAddQuery);
                addDialog.showModal();
                sendAddButton.addEventListener('click', sendAddData)
            })

            function sendAddData(e) {
                e.preventDefault();
                let inputData = {};
                inputList.forEach(function (value) {
                    if (value.name) inputData[value.name] = value.value;
                })
                sendData('/api/v1/user/add', inputData).then(function (data) {
                    clearErrors(clearAddQuery);
                    switch (data.status) {
                        case 'success':
                            addUserInTable(data.data)
                            addDialog.close();
                            break;
                        case 'error':
                            errorHandler(data.messages, 'err-', 'inp-add-');
                            break;
                        default:
                            console.log(data)
                    }
                });
            }

            function addUserInTable(data) {
                let tr = document.createElement('TR');
                tr.id = 'user' + data.id;
                tr.draggable = true;
                tr.classList.add('draggable');
                tr.dataset.userId = data['id'];
                createTd(tr, data['id']);
                createTd(tr, data['login'], cellNames.login);
                createTd(tr, data['name'], cellNames.name);
                createTd(tr, data['email'], cellNames.email);
                createTd(tr, data['address'], cellNames.address);
                createTd(tr, "<button>\n" +
                    "                            <svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 576 512\">\n" +
                    "                                <path d=\"M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z\"></path>\n" +
                    "                            </svg>\n" +
                    "                        </button>\n", cellNames.edit);
                createTd(tr, '<input type="checkbox">');
                tbody.append(tr);
                initEditButtons();
            }

            function createTd(tr, text, class_) {
                let td = document.createElement('TD');
                td.innerHTML = text;
                if (class_) td.classList.add(class_);
                tr.append(td);
                return td;
            }

            document.getElementById('show-password').addEventListener('change', function (e) {
                document.getElementById('inp-add-password').type = e.target.checked ? 'text' : 'password';
            })
        }

// ############################### Removal ##################################################

        const deleteBut = document.getElementById('delete-button');
        if (deleteBut) {
            let deleteArray = [];

            tbody.addEventListener('change', function (e) {
                const checkbox = e.target;
                if (checkbox.tagName !== 'INPUT' || checkbox.getAttribute('type') !== "checkbox") return;
                const userId = +checkbox.closest('tr').dataset.userId;
                if (checkbox.checked) deleteArray.push(userId);
                else deleteArray = deleteArray.filter(n => n !== userId);
                deleteBut.style.display = deleteArray.length > 0 ? 'block' : 'none';
            })
            if (deleteBut) {
                deleteBut.addEventListener('click', function (e) {
                    sendData('/api/v1/user/remove', deleteArray).then(function (data) {
                        if (data.data) clearRows(data.data);
                        if (data.status !== 'success') console.log($data);
                    });
                })
            }

            function clearRows(data) {
                for (let item of data) {
                    const elem = document.getElementById('user' + item);
                    if (elem) elem.remove();
                }
                deleteArray = [];
                deleteBut.style.display = 'none';
            }
        }
    }
)