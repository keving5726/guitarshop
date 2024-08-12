'use strict'

function activeModals(el) {
    let target = document.getElementById(el.dataset.target)
    document.documentElement.classList.add('is-clipped')
    target.classList.add('is-active')
}

function closeModals() {
    document.documentElement.classList.remove('is-clipped')
    let modals = document.getElementsByClassName('modal')

    for (let item of modals) {
        item.classList.remove('is-active')
    }
}

function getAll(selector) {
    return Array.prototype.slice.call(document.querySelectorAll(selector), 0)
}

export { activeModals, closeModals, getAll }
