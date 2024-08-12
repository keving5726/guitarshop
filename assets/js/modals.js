'use strict'

import { activeModals, closeModals, getAll } from './functions.js'

document.addEventListener('DOMContentLoaded', () => {
    let $modalButtons = getAll('.modal-button')
    let $modalCloses = getAll('.modal-background, .modal-close, .modal-card-head .delete, .modal-card-foot .button')

    if ($modalButtons.length > 0) {
        $modalButtons.forEach((el) => {
            el.addEventListener('click', () => {
                activeModals(el)
            })
        })
    }

    if ($modalCloses.length > 0) {
        $modalCloses.forEach((el) => {
            el.addEventListener('click', () => {
                closeModals()
            })
        })
    }

    document.addEventListener('keydown', (event) => {
        let e = event || window.event
        if (e.keyCode === 27) {
            closeModals()
        }
    })
})
