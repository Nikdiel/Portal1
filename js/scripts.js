// const params = new URLSearchParams(window.location.search);

// const lect = params.get('lect');
// const pg = params.get('pg');

// window.location.href = `index.php?lect=${lect}&pg=${pg}`;

document.getElementById("modalCancel").addEventListener("click", function(){
    document.getElementById("modalOverlay").className = "modal-overlay hidden";
})

// for (let i = 1; i <= deleteBtnCount; i++) {
//     const deleteBtn = document.getElementById('deleteBtn-'+i);
//     deleteBtn.addEventListener("click", function(){
//         window.location.href = `${deleteBtn.dataset.href}`;
//     })
// }

document.getElementById('btnAddLecture').addEventListener("click", function() {
  document.getElementById("modalOverlay").className = "modal-overlay";

  const params = new URLSearchParams(window.location.search);
  const lect = params.get('lect');
  const pg = params.get('pg');

  const modalForm = document.getElementById('modalForm');
  modalForm.setAttribute('action', `addLecture.php?lect=${lect}&pg=${pg}`);
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

  // слушатель кнопки "Сохранить"
  const modalSave = document.getElementById("modalSave");
  modalSave.onclick = function(e) {
    e.preventDefault(); // чтобы не перезагружало страницу
    const name = modalBody.querySelector('input[name="name"]').value.trim();
    const content = modalBody.querySelector('textarea[name="content"]').value.trim();
    const group = modalBody.querySelector('input[name="group"]').value.trim(); 

    if (name !== "" && content !== "" && group !== "") {
      document.getElementById("modalOverlay").className = "modal-overlay hidden";
      modalForm.submit(); // можно отправить форму, если хочешь
    } else {
      alert("Заполни все поля!");
    }
  };
});


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

if(document.getElementById('addQuetion')){
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
}


if(document.getElementById('addOptions')){
    document.getElementById('addOptions').addEventListener("click", function(){
        document.getElementById("modalOverlay").className = "modal-overlay";

        const params = new URLSearchParams(window.location.search);

        const lect = params.get('lect');
        const pg = params.get('pg');
        const q = this.dataset.id;

        document.getElementById('modalForm').setAttribute('action', `addOptions.php?lect=${lect}&pg=${pg}&q=${q}`);
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
}

document.getElementById('addGroupBtn').addEventListener("click", function(){
    document.getElementById("modalOverlay").className = "modal-overlay";

    const params = new URLSearchParams(window.location.search);

    let lect = '';
    let pg = '';

    if (params.has('lect') && params.get('lect').trim() !== '') {
    lect = params.get('lect');
    }

    if (params.has('pg') && params.get('pg').trim() !== '') {
    pg = params.get('pg');
    }

    if(lect!=''&&pg!=''){
        document.getElementById('modalForm').setAttribute('action', `addGroup.php?lect=${lect}&pg=${pg}`);
    }else{
        document.getElementById('modalForm').setAttribute('action', `addGroup.php`);
    }
    
    document.getElementById("modalTitle").textContent = "Создать группу";
    const modalBody = document.getElementById("modalBody");
    
    modalBody.innerHTML = `
        <label for="name">Название:</label>
        <input type="text" name="name" placeholder="Введите название группы" required>
    `;

    document.getElementById("modalSave").addEventListener("click", function(){
        const name = this.querySelector('input[name="name"]').value.trim();
        if (name !== "") {
            document.getElementById("modalOverlay").className = "modal-overlay hidden";
        }
    })

})