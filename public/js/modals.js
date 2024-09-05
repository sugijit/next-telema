// VIEW モーダル



function openModal() {
    document.getElementById('settingsModal').classList.remove('hidden');
}
function openFieldModal() {
    fieldCount = document.querySelectorAll('#field-container > div').length; // Count existing fields
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
        <div class="flex align-center gap-3 mb-2" id="field-${fieldCount}">
            <button type="button" onclick="removeField(${fieldCount})" class="text-red-500 hover:text-red-700">削除</button>
            <p class="pt-1">${fieldCount}</p>
            <input class="text-xs rounded-md placeholder:text-[0.6rem]" type="text" name="field_name_${fieldCount}" placeholder="(英字) 例：result">
            <input class="text-xs rounded-md" type="text" name="field_value_${fieldCount}" placeholder="例：結果">
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
