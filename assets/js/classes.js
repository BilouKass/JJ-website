const inputs = document.getElementsByClassName("inputs")
const modifs = document.getElementsByClassName("modif_but")
const deletes = document.getElementsByClassName("button_delete")
const valids = document.getElementsByClassName("button_valid") 
const Add = document.getElementById('new_classe');
const main_container = document.getElementById('main-container')
const input_form = document.getElementById('form-new')

function stop_new() {
    main_container.lastChild.remove()
}

for (let index = 0; index < modifs.length; index++) {
    const element = modifs[index];
    element.addEventListener("click", function () {
        const id = element.getAttribute('id').slice(-1);
        for (let index = 0; index < inputs[id].children.length; index++) {
            const input_field = inputs[id].children[index];
            console.log(input_field);
            if (input_field.hasAttribute('readonly')) {
                input_field.removeAttribute('readonly');
            }
            else {
                input_field.style.display = 'inline-block';
            }

        }
        const valid = valids[id];
        valid.style.display = 'inline-block'
        element.replaceWith(valid)
    })
    
}

Add.addEventListener("click", function () {
    main_container.appendChild(input_form)
    main_container.lastChild.className = 'container-solid'
    main_container.lastChild.style.display = 'inline-block'

})