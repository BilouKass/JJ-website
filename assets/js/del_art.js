const buts = document.getElementsByClassName('del_but')
const popup = document.getElementById('popup')
const popup_button = popup.getElementsByTagName('input')
const btn_delete = document.getElementById('popup_delete')

function stop_popup() {
    popup.style.visibility = 'hidden'
    popup.style.zIndex = '-1';
    popup.parentElement.style.overflow = 'visible'
}
for (const event of popup_button) {
    event.addEventListener('click', function () {
            if (event.id === 'popup_cancel') {
                stop_popup()
                
            }
        }
    )
}

for (const but of buts) {
    but.addEventListener('click', function () {
        window.location.href="#start"
        popup.style.visibility = 'visible'
        popup.style.zIndex = 10000
        popup.parentElement.style.overflow = 'hidden'
        btn_delete.name = but.name
        document.addEventListener('mouseup', function(click) {
            if (click.target != popup.children[0]) {
                stop_popup()
            }
        })
    })
}
