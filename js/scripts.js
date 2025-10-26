// const params = new URLSearchParams(window.location.search);

// const lect = params.get('lect');
// const pg = params.get('pg');

// window.location.href = `index.php?lect=${lect}&pg=${pg}`;

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
        <input type="text" name="name" placeholder="Введите название лекции" required>
        
        <label for="content">Содержание:</label>
        <textarea name="content" placeholder="Введите содержание" required></textarea>

        <label for="group">Для группы:</label>
        <input type="text" name="group" placeholder="Введите группу" required>
    `;

    document.getElementById("modalSave").addEventListener("click", function(){
        const name = this.querySelector('input[name="name"]').value.trim();
        const content = this.querySelector('textarea[name="content"]').value.trim();
        const group = this.querySelector('input[name="group"]').value.trim(); 
        if (name !== "" || content !== "" || group !== "") {
            document.getElementById("modalOverlay").className = "modal-overlay hidden";
        }
    })

})

for (let i = 1; i <= lecture.length; i++) {
    const updateBtn = document.getElementById('updateLecture-'+lecture[i-1].id);
    updateBtn.addEventListener("click", function(){
        document.getElementById("modalOverlay").className = "modal-overlay";
    
        const params = new URLSearchParams(window.location.search);
    
        const lect = params.get('lect');
        const pg = params.get('pg');
    
        document.getElementById('modalForm').setAttribute('action', `updateLecture.php?lect=${lect}&pg=${pg}&li=${lecture[i-1].idLecture}`);
        document.getElementById("modalTitle").textContent = "Изменить лекцию";
        const modalBody = document.getElementById("modalBody");
        
        modalBody.innerHTML = `
            <label for="name">изменить название:</label>
            <input type="text" name="name" placeholder="Введите название лекции">
            
            <label for="content">Изменить содержание:</label>
            <textarea name="content" placeholder="Введите содержание"></textarea>
            
            <label for="group">Для группы:</label>
            <input type="text" name="group" placeholder="Введите группу">
        `;
        document.getElementById("modalSave").addEventListener("click", function(){
            document.getElementById("modalOverlay").className = "modal-overlay hidden";
        })

    })
}

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
        <textarea name="content" placeholder="Введите вопрос" required></textarea>

        <label>Варианты:</label>

        <label for="option-1"><input type="radio" name="radio" value="1" required><input type="text" name="option-1" placeholder="Вариант 1"></label>

        <label for="option-2"><input type="radio" name="radio" value="2"><input type="text" name="option-2" placeholder="Вариант 2"></label>

        <label for="option-3"><input type="radio" name="radio" value="3"><input type="text" name="option-3" placeholder="Вариант 3"></label>

        <label for="option-4"><input type="radio" name="radio" value="4"><input type="text" name="option-4" placeholder="Вариант 4"></label>
    `;

    document.getElementById("modalSave").addEventListener("click", function(){
        const content = this.querySelector('textarea[name="content"]').value.trim();
        const radio = this.querySelector('input[name="radio"]:checked').value.trim(); 
        if (content !== "" || radio) {
            document.getElementById("modalOverlay").className = "modal-overlay hidden";
        }
    })

})

document.getElementById('addOptions').addEventListener("click", function(){
    document.getElementById("modalOverlay").className = "modal-overlay";

    const params = new URLSearchParams(window.location.search);

    const lect = params.get('lect');
    const pg = params.get('pg');

    document.getElementById('modalForm').setAttribute('action', `addOptions.php?lect=${lect}&pg=${pg}`);
    document.getElementById("modalTitle").textContent = "Добавить вопрос";
    const modalBody = document.getElementById("modalBody");
    
    modalBody.innerHTML = `
        <label>Варианты:</label>

        <label for="option-1"><input type="radio" name="radio" value="1"><input type="text" name="option-1" placeholder="Вариант 1"></label>

        <label for="option-2"><input type="radio" name="radio" value="2"><input type="text" name="option-2" placeholder="Вариант 2"></label>

        <label for="option-3"><input type="radio" name="radio" value="3"><input type="text" name="option-3" placeholder="Вариант 3"></label>

        <label for="option-4"><input type="radio" name="radio" value="4"><input type="text" name="option-4" placeholder="Вариант 4"></label>
    `;

    document.getElementById("modalSave").addEventListener("click", function(){
        const opt1 = this.querySelector('input[name="option-1"]').value.trim();
        const opt2 = this.querySelector('input[name="option-2"]').value.trim();
        const opt3 = this.querySelector('input[name="option-3"]').value.trim();
        const opt4 = this.querySelector('input[name="option-4"]').value.trim();
        if (opt1 !== "" && opt2 !== "" && opt3 !== "" && opt4 !== "") {
            document.getElementById("modalOverlay").className = "modal-overlay hidden";
        }else{
            modalBody.innerHTML = `<p style="color:red;">Введите хотя бы один вариант</p>`;
        }
    })

})