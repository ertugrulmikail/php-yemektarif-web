<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin'])) {
    header('Location: ../kullanici_giris_ekrani.php');
}

include('constants.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.5.0/mdb.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        thead {
            background-color: #95a5a6;
        }

        tr {

            box-shadow: 0px 0px 9px 0px rgba(0, 0, 0, 0.1);
            border-radius: 3px;
            padding: 25px 30px;
            margin-bottom: 25px;

        }

        td th {
            border: none;
            display: flex;
            justify-content: space-between;
        }

        .list-group {
            border-radius: 0 0 12px 12px;
            width: 100%;
        }

        .list-group-item {
            text-align: center;
            border: none;
        }

        .list-group-item:hover {
            cursor: pointer;
            transform: scale(1.03);
            box-shadow: 0 9px 47px 11px rgba(51, 51, 51, 0.18);
            background-color: #95a5a6;
        }

        .leaderboard__name {
            color: black;

            font-size: 2vw;
            letter-spacing: 0.64px;
        }

        .table {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 7px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="padding: 5px 30px;">
        <div class="row">
            <div class="col-3 d-flex justify-content-center" style="padding: 0px;">
                <ul class="list-group">
                    <li class="list-group-item" onclick="getApi(1)">
                        <button style="border: none; background-color: transparent;"><span class="leaderboard__name">Adminler</span></button>
                    </li>
                    <li class="list-group-item" onclick="getApi(2)">
                        <button style="border: none; background-color: transparent;"><span class="leaderboard__name">Kullanıcılar</span></button>
                    </li>
                    <li class="list-group-item" onclick="getApi(3)">
                        <button style="border: none; background-color: transparent;"><span class="leaderboard__name">Kategoriler</span></button>
                    </li>
                    <li class="list-group-item" onclick="getApi(4)">
                        <button style="border: none; background-color: transparent;"><span class="leaderboard__name">Tarifler</span></button>
                    </li>
                    <li class="list-group-item" onclick="getApi(6)">
                        <button style="border: none; background-color: transparent;"><span class="leaderboard__name">Onaylanmayan Tarifler</span></button>
                    </li>
                    <li class="list-group-item" onclick="getApi(5)">
                        <button style="border: none; background-color: transparent;"><span class="leaderboard__name">Yorumlar</span></button>
                    </li>
                </ul>
            </div>
            <div class="col-9">
                <?php
                include('navbar.php');
                if (isset($_SESSION['edit_messsage'])) {
                    $edit_messsage = $_SESSION['edit_messsage'];

                    $edit_messsage = json_decode($edit_messsage);

                    echo "<div id='errorMessage' style='display: flex; justify-content: center;'>
                    <div class='alert alert-danger' role='alert' style='width: 40%; background-color: {$edit_messsage->color}; background-opacity: 0.8; color: white'>{$edit_messsage->text}</div>
                    </div>";
                    unset($_SESSION['edit_messsage']);
                }

                $process = null;
                if (isset($_SESSION['process'])) {
                    $process = $_SESSION['process'];
                    unset($_SESSION['process']);
                }
                ?>
                <div class="container mt-4">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="container" id="content" style="width: 100%; overflow-x:auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const adminFields = {
            id: 'int',
            username: 'string',
            password: 'string'
        };

        const userFields = {
            id: 'int',
            ad: 'string',
            soyad: 'string',
            email: 'string',
            kullanici_adi: 'string',
            sifre: 'string',
            profil_resmi: 'image'
        };

        const defterFields = {
            id: 'int',
            tarif_id: 'int',
            kullanici_id: 'int'
        };

        const kategoriFields = {
            id: 'int',
            kategori_adi: 'string'
        };

        const tarifFields = {
            id: 'int',
            kullanici_id: 'int',
            kategori_id: 'int',
            tarif_adi: 'string',
            malzemeler: 'string',
            hazirlanisi: 'string',
            kisi_sayisi: 'string',
            hazirlanma_suresi: 'string',
            pisirme_suresi: 'string',
            tarif_gorseli: 'image',
            durum: 'string',
            revize_sebebi: 'string',
            olusturulma_tarihi: 'date'
        };

        const yorumlarFields = {
            id: 'int',
            tarif_id: 'int',
            kullanici_id: 'int',
            yorum_metni: 'string',
            olusturulma_tarihi: 'timestamp'
        };

        function getClassByProcessId(processId) {
            switch (processId) {
                case 1:
                    return adminFields;
                case 2:
                    return userFields;
                case 3:
                    return kategoriFields;
                case 4:
                    return tarifFields;
                case 5:
                    return yorumlarFields;
                case 6:
                    return tarifFields;
                default:
                    return null;
            }
        }

        function getApi(processId) {
            if (processId < 7 && processId > 0) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            displayResult(response, processId);
                        } else {
                            console.log(xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'adminService.php?process=' + processId, true);
                xhr.send();
            }
        }

        function editItem(id, processId) {
            if (processId < 7 && processId > 0) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = xhr.responseText;
                            createEditForm(response, processId);
                        } else {
                            console.log(xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'editContent.php?id=' + id + '&process=' + processId, true);
                xhr.send();
            }
        }

        function createDetailForm(data, processId) {
            let className = getClassByProcessId(processId);
            var card = document.createElement('div');
            card.classList.add('card');
            card.style.overflowY = 'auto';
            card.style.maxHeight = '95vh';

            var cardHeader = document.createElement('div');
            var cardContent = document.createElement('div');

            cardHeader.classList.add('card-header');
            cardHeader.classList.add('d-flex', 'justify-content-center');

            cardContent.classList.add('card-body');
            cardContent.style.background = '#888';

            var saveButton = document.createElement('button');
            saveButton.textContent = 'Kaydet';
            saveButton.classList.add('btn', 'btn-primary');
            saveButton.disabled = true;

            var initialValues = {};

            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                    var inputType = 'text';
                    if (className[key] === 'int') {
                        inputType = 'number';
                    } else if (className[key] === 'date') {
                        inputType = 'datetime-local';
                    } else if (className[key] === 'image') {
                        inputType = 'file';
                    }

                    if (className[key] === 'image') {
                        var imgContainer = document.createElement('div');
                        imgContainer.style.position = 'relative';
                        imgContainer.style.width = '100%';
                        imgContainer.style.maxWidth = '300px';
                        imgContainer.style.maxHeight = '300px';
                        imgContainer.style.overflow = 'hidden';

                        if (data[key]) {
                            var baseName = data[key].replace(/\.[^/.]+$/, "");
                            var extensions = ['jpg', 'jpeg', 'png'];
                            var found = false;

                            for (let ext of extensions) {
                                let imgPath = `../Kullanici_Profil_Resimleri/${baseName}.${ext}`;
                                if (processId == 4 || processId == 6)
                                    imgPath = `../Kullanici_Tarif_Resimleri/${baseName}.${ext}`;
                                let xhr = new XMLHttpRequest();
                                xhr.open('HEAD', imgPath, false);
                                xhr.send();

                                if (xhr.status != 404) {
                                    var img = document.createElement('img');
                                    img.setAttribute('src', imgPath);
                                    img.style.width = '100%';
                                    img.style.height = 'auto';
                                    img.style.display = 'block';
                                    imgContainer.appendChild(img);

                                    var overlay = document.createElement('div');
                                    overlay.style.position = 'absolute';
                                    overlay.style.top = '0';
                                    overlay.style.left = '0';
                                    overlay.style.width = '100%';
                                    overlay.style.height = '100%';
                                    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                                    overlay.style.display = 'flex';
                                    overlay.style.flexDirection = 'column';
                                    overlay.style.alignItems = 'center';
                                    overlay.style.justifyContent = 'center';
                                    overlay.style.color = '#fff';
                                    overlay.style.opacity = '0';
                                    overlay.style.transition = 'opacity 0.3s';

                                    var icon = document.createElement('i');
                                    icon.classList.add('fa', 'fa-eye');
                                    icon.style.cursor = 'pointer';
                                    icon.setAttribute('aria-hidden', 'true');

                                    var text = document.createElement('span');
                                    text.textContent = 'Değiştir';
                                    text.style.cursor = 'pointer';

                                    overlay.appendChild(icon);
                                    overlay.appendChild(text);

                                    imgContainer.appendChild(overlay);

                                    imgContainer.addEventListener('mouseover', function() {
                                        overlay.style.opacity = '1';
                                    });

                                    imgContainer.addEventListener('mouseout', function() {
                                        overlay.style.opacity = '0';
                                    });

                                    overlay.addEventListener('click', function() {
                                        fileInput.click();
                                    });

                                    var imageChanged = false;

                                    var fileInput = document.createElement('input');
                                    fileInput.name = data[key];
                                    fileInput.type = 'file';
                                    fileInput.style.display = 'none';
                                    fileInput.addEventListener('change', function(event) {
                                        var file = event.target.files[0];
                                        if (file) {
                                            var reader = new FileReader();
                                            reader.onload = function(e) {
                                                img.src = e.target.result;
                                                imageChanged = true;
                                                saveButton.disabled = false;
                                            };
                                            reader.readAsDataURL(file);
                                        }
                                    });

                                    imgContainer.appendChild(fileInput);
                                    cardHeader.appendChild(imgContainer);
                                    found = true;
                                    break;
                                }
                            }

                            if (!found) {
                                imgContainer.textContent = 'No image found';
                            }
                        }

                        if (!data[key]) {
                            var fileInput = document.createElement('input');
                            fileInput.name = key;
                            fileInput.type = 'file';
                            fileInput.classList.add('form-control');
                            fileInput.addEventListener('change', function() {
                                saveButton.disabled = false;
                            });
                            cardHeader.appendChild(fileInput);
                        }

                    } else {
                        var formGroup = document.createElement('div');
                        formGroup.classList.add('form-group', 'mb-2');

                        var label = document.createElement('label');
                        label.classList.add('d-flex', 'justify-content-center');
                        label.setAttribute('for', key);
                        label.textContent = key.replace(/_/g, ' ').toUpperCase();

                        var input = document.createElement('input');
                        input.type = inputType;
                        input.classList.add('form-control');
                        input.id = key;
                        input.name = key;
                        input.value = data[key];
                        initialValues[key] = data[key];

                        input.addEventListener('input', function() {
                            saveButton.disabled = false;
                        });

                        formGroup.appendChild(label);
                        formGroup.appendChild(input);
                        cardContent.appendChild(formGroup);
                    }
                }
            }
            console.log(initialValues);
            saveButton.addEventListener('click', function() {
                var formData = new FormData();
                var inputs = card.querySelectorAll('input');

                var hasFormChanges = false;
                var hasImageChanges = imageChanged;

                inputs.forEach(function(input) {
                    if (input.type !== 'file') {
                        var formattedInputValue = input.value;

                        if (input.type === 'datetime-local') {
                            var inputDate = new Date(input.value);
                            var year = inputDate.getFullYear();
                            var month = ('0' + (inputDate.getMonth() + 1)).slice(-2);
                            var day = ('0' + inputDate.getDate()).slice(-2);
                            var hour = ('0' + inputDate.getHours()).slice(-2);
                            var minute = ('0' + inputDate.getMinutes()).slice(-2);
                            var second = ('0' + inputDate.getSeconds()).slice(-2);

                            formattedInputValue = year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
                            formattedInputValue = formattedInputValue.replace('T', ' ');
                        }

                        if (formattedInputValue !== initialValues[input.name]) {
                            formData.append(input.name, formattedInputValue);
                            hasFormChanges = true;
                        }
                    }
                });

                if (hasFormChanges || hasImageChanges) {
                    if (hasFormChanges) {
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'update.php?id=' + data['id'] + '&process=' + processId, true);
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                console.log('Form verileri güncellendi');
                                if (!hasImageChanges) {
                                    getApi(processId);
                                }
                            } else {
                                alert('Form verilerini güncellerken bir hata oluştu');
                            }
                        };
                        xhr.send(formData);
                    }

                    if (hasImageChanges) {
                        deleteImageAndUploadNew(data, processId);
                        getApi(processId);
                    }
                }
            });

            var formGroup = document.createElement('div');
            formGroup.classList.add('form-group', 'mb-2');
            formGroup.classList.add('d-flex', 'justify-content-center');
            formGroup.appendChild(saveButton);

            card.appendChild(cardHeader);
            card.appendChild(cardContent);
            cardContent.appendChild(formGroup);

            let content = document.getElementById('content');
            content.innerHTML = '';
            content.appendChild(card);
        }

        function deleteImageAndUploadNew(data, processId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'deleteImage.php?id=' + data['id'] + '&process=' + processId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('Mevcut resim silindi');
                    uploadNewImage(data, processId);
                } else {
                    alert('Mevcut resmi silerken bir hata oluştu');
                }
            };
            xhr.send();

        }

        function uploadNewImage(data, processId) {
            var card = document.getElementsByClassName('card')[0];
            var fileInput = card.querySelector('input[type="file"]');
            var imageFile = fileInput.files[0];
            if (imageFile) {
                var imageFormData = new FormData();
                imageFormData.append('image', imageFile);
                imageFormData.append('filename', data['id']);

                var imageXhr = new XMLHttpRequest();
                imageXhr.open('POST', 'updateImage.php?id=' + data['id'] + '&process=' + processId, true);
                imageXhr.onload = function() {
                    if (imageXhr.status === 200) {
                        console.log('Yeni resim dosyası yüklendi');
                    } else {
                        alert('Yeni resim dosyasını yüklerken bir hata oluştu');
                    }
                };
                imageXhr.send(imageFormData);
            }
        }

        function showDetail(id, processId) {
            if (processId < 7 && processId > 0) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.status === 1) {
                                createDetailForm(response.data, processId);
                            }
                        } else {
                            console.log(xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'detail.php?id=' + id + '&process=' + processId, true);
                xhr.send();
            }
        }

        function verifyFood(id, processId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = xhr.responseText;
                        getApi(processId);
                    } else {
                        console.log(xhr.status);
                    }
                }
            };
            xhr.open('GET', 'verifyFood.php?id=' + id, true);
            xhr.send();
        }

        function removeItem(id, processId) {
            /*if (processId < 6 && processId > 0) {
                showRemoveDialog(id, processId);
            }*/
            if (processId < 6 && processId > 0) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = xhr.responseText;
                            getApi(processId);

                        } else {
                            console.log(xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'removeContent.php?id=' + id + '&process=' + processId, true);
                xhr.send();
            }
        }

        function appendItem(processId) {
            if (processId < 6 && processId > 0) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = xhr.responseText;
                            createNewForm(response, processId);
                        } else {
                            console.log(xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'appendContent.php?process=' + processId, true);
                xhr.send();
            }
        }

        function showRemoveDialog(id, processId) {
            let html = `
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Silme işlemi</h5>
                    <p class="card-text">${id} id'li kaydı silmek üzeresiniz. Onaylıyor musunuz?</p>
                    <a class="btn btn-primary">Onayla</a>
                    <a class="btn btn-danger">İptal</a>
                </div>
            </div>
            `;
            document.getElementById('content').innerHTML = html;
        }

        function createNewForm(data, processId) {
            var formHTML = '<form id="createForm" method="post">';
            if (JSON.parse(data)) {
                let tableData = JSON.parse(data);
                tableData.forEach(function(column) {
                    if (column.Field !== "id") {
                        formHTML += "<div class='form-group mb-1'>";
                        formHTML += "<label class='d-flex justify-content-center' for='" + column.Field + "'>" + column.Field + "</label>";

                        if (column.Type.includes("varchar")) {
                            formHTML += "<input type='text' class='form-control' id='" + column.Field + "' name='" + column.Field + "'>";
                        } else if (column.Type.includes("int")) {
                            formHTML += "<input type='number' class='form-control' id='" + column.Field + "' name='" + column.Field + "'>";
                        } else {
                            formHTML += "<input type='text' class='form-control' id='" + column.Field + "' name='" + column.Field + "'>";
                        }

                        formHTML += "</div>";
                    }
                });

                formHTML += "<button type='submit' class='btn btn-primary'>Submit</button>";
                formHTML += "</form>";
            }

            let action = 'append.php?process=';

            switch (processId) {
                case 1:
                    action += '1';
                    break;
                case 2:
                    action += '2';
                    break;
                case 3:
                    action += '3';
                    break;
                case 4:
                    action += '4';
                    break;
                case 5:
                    action += '5';
                    break
            }

            document.getElementById('content').innerHTML = formHTML;
            document.forms[0].action = action;
        }

        function createEditForm(entries, processId) {
            let parsedEntries = Object.entries(JSON.parse(entries));
            let htmlCode = '<form id="editForm" method="post">';

            let id = null;

            parsedEntries.forEach(([k, v]) => {
                let isDisabled = (k === 'id');
                if (isDisabled)
                    id = v;
                htmlCode += "<div class='form-group mb-1'>";
                htmlCode += "<label class='d-flex justify-content-center' for='" + k + "'>" + k + "</label>";
                htmlCode += `<input type='text' class='form-control' id='${k}' name='${k}' value='${v}' ${isDisabled ? 'disabled' : ''}></input>`;
                htmlCode += "</div>";
            });

            htmlCode += "<div class='form-group d-flex justify-content-center mb-1'> <button type='submit' class='btn btn-success'>Güncelle</button> </div>";
            htmlCode += '</form>';

            let action = 'update.php?id=' + id + '&process=' + processId;

            /*switch (processId) {
                case 1:
                    action += '1';
                    break;
                case 2:
                    action += '2';
                    break;
                case 3:
                    action += '3';
                    break;
                case 4:
                    action += '4';
                    break;
                case 5:
                    action += '5';
                    break
            }*/

            document.getElementById('content').innerHTML = htmlCode;
            document.forms[0].action = action;
        }

        function displayResult(entries, process) {
            var table = '<table class="table">';
            let keys = [];
            if (entries.length > 0) {
                keys = Object.keys(entries[0]);
                if (keys.length) {
                    table += '<thead><tr><th scope="col"></th>';
                }
                if (process == 6) {
                    table += '<th scope="col"></th>';
                }
                keys.forEach(key => {
                    table += `
                        <th scope="col">
                            ${key}
                        </th>
                    `;
                });
                if (keys.length) {
                    table += '<th scope="col"></th> <th scope="col"></th></tr></thead>';
                }

                table += '<tbody>';

                entries.forEach(entry => {
                    table += '<tr>';
                    if (process == 6) {
                        table += '<td scope="col"><i class="fa-solid fa-check" style="cursor: pointer;" onclick="verifyFood(' + entry['id'] + ',' + process + ')"></i></td>';
                    }
                    table += '<td scope="col"><i class="fa fa-eye" style="cursor: pointer;" aria-hidden="true" onclick="showDetail(' + entry['id'] + ',' + process + ')"></i></td>';
                    keys.forEach(key => {
                        table += '<td scope="col">' + entry[key] + '</td>';
                    });
                    //table += '<td scope="col"> <i class="fas fa-edit" style="cursor: pointer;" onclick="editItem(' + entry['id'] + ',' + process + ')" > </i></td>';
                    table += '<td scope="col"> <i class="fas fa-trash" style="cursor: pointer;" onclick="removeItem(' + entry['id'] + ',' + process + ')" > </i></td>';
                    table += '</tr>';
                });

            }
            table += '<tr> <td colspan=' + (keys.length + 3) + '><i class="fas fa-plus" style="cursor: pointer;" onclick="appendItem(' + process + ')" ></i></td> </tr>';
            table += '</tbody></table>';
            document.getElementById('content').innerHTML = table;

        }

        document.addEventListener('DOMContentLoaded', function() {
            var errorMessage = document.getElementById('errorMessage');
            if (!errorMessage)
                return;

            errorMessage.style.display = 'flex';

            var process = <?php echo json_encode($process); ?>;
            if (process) {
                console.log(process);
                getApi(process);
            }

            setTimeout(function() {
                errorMessage.style.display = 'none';
            }, 3000);
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.5.0/mdb.min.js"></script>
    <script src="https://kit.fontawesome.com/628c8d2499.js" crossorigin="anonymous"></script>
</body>

</html>