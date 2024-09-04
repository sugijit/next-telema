// VIEW モーダル

function makeEditable(rowIndex, colIndex) {
    console.log(rowIndex);
    console.log(colIndex);
    
    const textDiv = document.getElementById(`editable-text-${rowIndex}-${colIndex}`);
    const inputField = document.getElementById(`editable-input-${rowIndex}-${colIndex}`);
    textDiv.style.display = 'none';
    inputField.style.display = 'block';
    inputField.focus();
}

function saveChanges(rowIndex, colIndex) {
    const id = "{{ $id }}";
    const textDiv = document.getElementById(`editable-text-${rowIndex}-${colIndex}`);
    const inputField = document.getElementById(`editable-input-${rowIndex}-${colIndex}`);
    const newValue = inputField.value;

    // データをサーバーに送信
    fetch('/update-cell', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            rowIndex: rowIndex,
            colIndex: colIndex,
            value: newValue,
            id: id,
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (newValue == null || newValue == "" || newValue == " " || newValue == "　") {
                textDiv.textContent = "-";
            } else {
                textDiv.textContent = newValue;
            }
        } else {
            alert('変更の保存に失敗しました');
        }
        textDiv.style.display = 'block';
        inputField.style.display = 'none';
        if(newValue == null || newValue == "") {
            textDiv.value = "-";
        }

    })
    .catch(error => {
        console.error('Error:', error);
        alert('エラーが発生しました');
        textDiv.style.display = 'block';
        inputField.style.display = 'none';
    });
}
function resizeTable() {
    console.log('aaa');
}


function openModal() {
    document.getElementById('settingsModal').classList.remove('hidden');
}
function openFieldModal() {
    document.getElementById('settingsFieldModal').classList.remove('hidden');
}

function closeModal(event) {
    if (event) {
        event.stopPropagation();
    }
    document.getElementById('settingsModal').classList.add('hidden');
}
function closeModal(event) {
    if (event) {
        event.stopPropagation();
    }
    document.getElementById('settingsModal').classList.add('hidden');
}
function closeFieldModal(event) {
    if (event) {
        event.stopPropagation();
    }
    document.getElementById('settingsFieldModal').classList.add('hidden');
}


// ADD FIELD モーダル
let fieldCount = 0;

function addField() {
    fieldCount++;
    const container = document.getElementById('field-container');

    const fieldHTML = `
        <div class="flex align-center gap-3 mb-2">
            <p class="pt-1">${fieldCount}</p>
            <input class="text-xs rounded-md placeholder:text-[0.5rem]" type="text" name="field_name_${fieldCount}" placeholder="英小文字">
            <input class="text-xs rounded-md" type="text" name="field_value_${fieldCount}" placeholder="フィールド">
            <select class="text-xs rounded-md" name="field_type_${fieldCount}" onchange="toggleOptions(${fieldCount}, this)">
                <option value="text">テキスト式</option>
                <option value="select">選択式</option>
            </select>
            <div id="options-container-${fieldCount}" class="hidden">
                <input class="text-xs rounded-md" type="text" name="options_${fieldCount}" placeholder="選択肢 (カンマで区切る)">
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', fieldHTML);
}

function toggleOptions(fieldId, selectElement) {
    const optionsContainer = document.getElementById(`options-container-${fieldId}`);
    if (selectElement.value === "select") {
        optionsContainer.classList.remove('hidden');
    } else {
        optionsContainer.classList.add('hidden');
    }
}