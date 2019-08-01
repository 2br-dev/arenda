import React from 'react'

import PaymentsView from './views/PaymentsView'

import { BACKEND } from '../path.js'


class Payments extends React.Component {

    constructor(props) {
        super(props)
        this.state = {
            addError: '',
            payments: []
        }
        this.getPayments()
    }

    addSubmit(e) {
        e.preventDefault()
        
    }
        
        
    getPayments() {
        
        
    }


    render() {
        return (
            <PaymentsView />
        )
    }
}


export default Payments