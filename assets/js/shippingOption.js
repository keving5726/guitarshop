'use strict'

let shippingSelect = document.getElementById('shippingSelect')

shippingSelect.addEventListener('change', (event) => {
    let selectedValue = event.target.value

    new Promise((resolve, reject) => {
        let url = window.location
        let data = new FormData()
        data.append('shippingOption', selectedValue)
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
            document.getElementById('totalBeforeTax').innerHTML = 'USD ' + response.totalBeforeTax
            document.getElementById('tax').innerHTML = 'USD ' + response.tax + ' (3%)'
            document.getElementById('total').innerHTML = 'USD ' + response.total
        })
        .catch((error) => {
            console.log(error)
        })
})
