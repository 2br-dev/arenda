// функции для запроса данных от сервера

import { BACKEND } from './path'


export function getUsers() {
        return new Promise((onFulfilled , onRejected ) => {
            const URL = BACKEND + 'getUser'
            const OPTIONS = {
                mode: 'cors'
            }
    
            fetch(URL,OPTIONS)
                .then(response => response.json())
                .then(response => {
                    if(response != null) {
                        onFulfilled(response)
                    } else {
                        onRejected('ошибка сервера')
                    }
                })
        })
}


export function getContracts() {
        return new Promise((onFulfilled , onRejected ) => {
            const URL = BACKEND + 'getContract'
            const OPTIONS = {
                mode: 'cors'
            }
    
            fetch(URL,OPTIONS)
                .then(response => response.json())
                .then(response => {
                    if(response != null) {
                        onFulfilled(response)
                    } else {
                        onRejected('ошибка сервера')
                    }
                })
        })
}


export function getInvoices() {
        return new Promise((onFulfilled , onRejected ) => {
            const URL = BACKEND + 'getInvoice'
            const OPTIONS = {
                mode: 'cors'
            }
    
            fetch(URL,OPTIONS)
                .then(response => response.json())
                .then(response => {
                    if(response != null) {
                        onFulfilled(response)
                    } else {
                        onRejected('ошибка сервера')
                    }
                })
        })
}


export function getPayments() {
        return new Promise((onFulfilled , onRejected ) => {
            const URL = BACKEND + 'getPayment'
            const OPTIONS = {
                mode: 'cors'
            }
    
            fetch(URL,OPTIONS)
                .then(response => response.json())
                .then(response => {
                    if(response != null) {
                        onFulfilled(response)
                    } else {
                        onRejected('ошибка сервера')
                    }
                })
        })
}


export function getRegistry() {
        return new Promise((onFulfilled , onRejected ) => {
            const URL = BACKEND + 'getRegistry'
            const OPTIONS = {
                mode: 'cors'
            }
    
            fetch(URL,OPTIONS)
                .then(response => response.json())
                .then(response => {
                    if(response != null) {
                        onFulfilled(response)
                    } else {
                        onRejected('ошибка сервера')
                    }
                })
        })
}