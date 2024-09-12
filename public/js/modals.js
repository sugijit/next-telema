// VIEW モーダル



function openModal() {
    document.getElementById('settingsModal').classList.remove('hidden');
}
function openFieldModalAdd() {
    fieldCount = document.querySelectorAll('#field-container1 > div').length; // Count existing fields
    document.getElementById('settingsFieldModalAdd').classList.remove('hidden');

}
function openFieldModalDelete() {
    fieldCount = document.querySelectorAll('#field-container2 > div').length; // Count existing fields
    document.getElementById('settingsFieldModalDelete').classList.remove('hidden');
}
function openFieldModalUpdate() {
    document.getElementById('settingsFieldModalUpdate').classList.remove('hidden');
}

function closeModal(event) {
    if (event) {
        event.stopPropagation();
    }
    document.getElementById('settingsModal').classList.add('hidden');
}

function closeFieldModalAdd(event) {
    if (event) {
        event.stopPropagation();
    }
    document.getElementById('settingsFieldModalAdd').classList.add('hidden');
}
function closeFieldModalDelete(event) {
    if (event) {
        event.stopPropagation();
    }
    document.getElementById('settingsFieldModalDelete').classList.add('hidden');
}
function closeFieldModalUpdate(event) {
    if (event) {
        event.stopPropagation();
    }
    document.getElementById('settingsFieldModalUpdate').classList.add('hidden');
}


// ADD FIELD モーダル
let fieldCount = 0;

function addField() {
    fieldCount++;
    const container = document.getElementById('field-container1');

    const fieldHTML = `
        <div class="flex align-center gap-3 mb-2 newly-added" id="field-${fieldCount}">
            <p class="pt-1">${fieldCount}</p>
            <input class="text-xs rounded-md placeholder:text-[0.6rem]" type="text" name="field_name_${fieldCount}" placeholder="(英字) 例：result">
            <input class="text-xs rounded-md" type="text" name="field_value_${fieldCount}" placeholder="例：結果">
            <select class="text-xs rounded-md" name="field_type_${fieldCount}" onchange="toggleOptions(${fieldCount}, this)">
                <option value="text">テキスト式</option>
                <option value="select">選択式</option>
                <option value="date">日付</option>
            </select>
            <div id="options-container1-${fieldCount}" class="hidden">
                <input class="text-xs rounded-md" type="text" name="options_${fieldCount}" placeholder="選択肢 (カンマで区切る)">
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', fieldHTML);
}

function toggleOptions(fieldId, selectElement) {
    const optionsContainer = document.getElementById(`options-container1-${fieldId}`);
    if (selectElement.value === "select") {
        optionsContainer.classList.remove('hidden');
    } else {
        optionsContainer.classList.add('hidden');
    }
}
function toggleOptionsUpdate(fieldId, selectElement) {
    const optionsContainer = document.getElementById(`options-container-${fieldId}`);
    if (selectElement.value === "select") {
        optionsContainer.classList.remove('hidden');
    } else {
        optionsContainer.classList.add('hidden');
    }
}
