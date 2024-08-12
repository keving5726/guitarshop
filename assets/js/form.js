'use strict'

import { activeModals } from './functions.js'

let formButton = document.getElementsByClassName('form-button')

for (let item of formButton) {
    item.addEventListener('click', (event) => {
        event.preventDefault()
        activeModals(item)

        let form = document.getElementById(item.dataset.form)
        let formSubmit = document.getElementsByClassName('form-submit')
        for (let item of formSubmit) {
            item.addEventListener('click', () => {
                form.submit()
            })
        }
    })
}
