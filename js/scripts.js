
// const params = new URLSearchParams(window.location.search);

// const lect = params.get('lect');
// const pg = params.get('pg');

// window.location.href = `index.php?lect=${lect}&pg=${pg}`;

document.getElementById("modalSave").addEventListener("click", function(){
    document.getElementById("modalOverlay").className = "modal-overlay hidden";
})

document.getElementById("modalCancel").addEventListener("click", function(){
    document.getElementById("modalOverlay").className = "modal-overlay hidden";
})

document.getElementById('btnAddLecture').addEventListener("click", function(){
    document.getElementById("modalOverlay").className = "modal-overlay";

    const params = new URLSearchParams(window.location.search);

    const lect = params.get('lect');
    const pg = params.get('pg');

    document.getElementById('modalForm').setAttribute('action', `addLecture.php?lect=${lect}&pg=${pg}`);
    document.getElementById("modalTitle").textContent = "Создать лекцию";
    const modalBody = document.getElementById("modalBody");
    
    modalBody.innerHTML = `
        <label for="name">Название:</label>
        <input type="text" name="name">
        
        <label for="content">Содержание:</label>
        <textarea name="content"></textarea>

        <label for="group">Для группы:</label>
        <input type="text" name="group">
    `;
})

document.getElementById('updateLecture').addEventListener("click", function(){
    document.getElementById("modalOverlay").className = "modal-overlay";

    const params = new URLSearchParams(window.location.search);

    const lect = params.get('lect');
    const pg = params.get('pg');

    document.getElementById('modalForm').setAttribute('action', `updateLecture.php?lect=${lect}&pg=${pg}`);
    document.getElementById("modalTitle").textContent = "Изменить лекцию";
    const modalBody = document.getElementById("modalBody");
    
    modalBody.innerHTML = `
        <label for="name">изменить название:</label>
        <input type="text" name="name">
        
        <label for="content">Изменить содержание:</label>
        <textarea name="content"></textarea>
        
        <label for="group">Для группы:</label>
        <input type="text" name="group">
    `;
})

document.getElementById('addQuetion').addEventListener("click", function(){
    document.getElementById("modalOverlay").className = "modal-overlay";

    const params = new URLSearchParams(window.location.search);

    const lect = params.get('lect');
    const pg = params.get('pg');

    document.getElementById('modalForm').setAttribute('action', `addQuetion.php?lect=${lect}&pg=${pg}`);
    document.getElementById("modalTitle").textContent = "Добавить вопрос";
    const modalBody = document.getElementById("modalBody");
    
    modalBody.innerHTML = `
        <label for="content">Содержание:</label>
        <textarea name="content"></textarea>

        <label>Варианты:</label>

        <label for="option-1"><input type="radio" name="radio" value="1"><input type="text" name="option-1"></label>

        <label for="option-2"><input type="radio" name="radio" value="2"><input type="text" name="option-2"></label>

        <label for="option-3"><input type="radio" name="radio" value="3"><input type="text" name="option-3"></label>

        <label for="option-4"><input type="radio" name="radio" value="4"><input type="text" name="option-4"></label>
    `;
})