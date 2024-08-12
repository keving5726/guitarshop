'use strict'

import { activeModals } from './functions.js'

let rating = document.getElementsByClassName('rating-link')
let ratingButton = document.getElementById('rating-button')
let vote

for (let item of rating) {
    item.addEventListener('click', () => {
        vote = item.dataset.vote;
        activeModals(item)
    })
}

ratingButton.addEventListener('click', () => {
    new Promise((resolve, reject) => {
        let url = window.location
        let data = new FormData()
        data.append('vote', vote)
        let xhr = new XMLHttpRequest()
        xhr.addEventListener('load', () => {
            if (xhr.readyState == 4 && xhr.status == 200) {
                resolve(JSON.parse(xhr.responseText))
            } else {
                reject("Error: " + xhr.responseText)
            }
        })
        xhr.open("POST", url)
        xhr.send(data);
    })
        .then((response) => {
            if (response.message === undefined) {
                for (let item of rating) {
                    if (item.dataset.vote <= response.average) {
                        item.children[0].classList.replace('far', 'fas')
                    }
                }
                document.getElementById('average').innerHTML = response.average
                document.getElementById(vote).innerHTML = vote + ' star = ' + response['vote_' + vote]
                notification('Your vote has been stored', 'success')
            } else {
                notification(response.message, response.type)
            }
        })
        .catch((error) => {
            console.log(error)
        })
})

function notification(message, type) {
    let child = document.getElementById('rating')
    let div = document.createElement('div')
    let button = document.createElement('button')
    let text = document.createTextNode(message)
    let parent = child.parentNode
    div.classList.add('notification', 'is-' + type, 'mx-5', 'my-6')
    div.id = 'notification'
    button.classList.add('delete')
    div.appendChild(button)
    div.appendChild(text)
    button.addEventListener('click', () => {
        div.parentNode.removeChild(document.getElementById('notification'))
    })
    parent.insertBefore(div, child)
}
